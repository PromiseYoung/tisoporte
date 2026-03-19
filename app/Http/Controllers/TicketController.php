<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Category;
use App\Localidad;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\DataChangeEmailNotification;
use App\Priority;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    use MediaUploadingTrait;

    public function create()
    {
        $categories = Cache::remember(
            'ticket_categories',
            600,
            fn() =>
            Category::select('id', 'name')->get()
        );

        $priorities = Cache::remember(
            'ticket_priorities',
            600,
            fn() =>
            Priority::select('id', 'name')->get()
        );

        $localidades = Cache::remember(
            'ticket_localidades',
            600,
            fn() =>
            Localidad::select('id', 'nombre')->get()
        );

        $users = Cache::remember(
            'ticket_users',
            600,
            fn() =>
            User::select('id', 'name')->get()
        );

        return view('tickets.create', compact(
            'categories',
            'priorities',
            'users',
            'localidades'
        ));
    }

    public function store(Request $request)
    {
        // ✅ Anti-spam mejorado (atómico)
        $submissionKey = 'ticket_submission:' . $request->ip() . ':' . $request->author_email;

        if (!Cache::add($submissionKey, true, 15)) {
            return back()->withErrors([
                'error' => 'Por favor espera unos segundos antes de enviar otro ticket.'
            ]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'category' => 'required|exists:categories,name',
            'priority' => 'required|exists:priorities,name',
            'localidad' => 'required|exists:localidades,nombre'
        ]);

        DB::beginTransaction();

        try {
            // 🔥 Manteniendo lógica original (por nombre)
            $category = Category::where('name', $validated['category'])->firstOrFail();
            $priority = Priority::where('name', $validated['priority'])->firstOrFail();
            $localidad = Localidad::where('nombre', $validated['localidad'])->firstOrFail();

            $user = $category->user_id ? User::find($category->user_id) : null;

            $ticket = Ticket::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'author_name' => $validated['author_name'],
                'author_email' => $validated['author_email'],
                'category_id' => $category->id,
                'priority_id' => $priority->id,
                'localidad_id' => $localidad->id,
                'assigned_to_user_id' => $user?->id,
                'status_id' => 1,
                'id' => (string) Str::uuid(),
            ]);

            // Adjuntos
            $this->processAttachments($ticket, $request);

            DB::commit();

            // 🔥 Notificaciones fuera de la transacción
            $this->sendNotifications($ticket);

            return redirect()->route('tickets.create')->withStatus(
                'Tu ticket ha sido enviado, nos pondremos en contacto contigo.
                Puedes ver el estado de tu solicitud aquí:
                <a href="' . route('tickets.show', $ticket->id) . '">Ver Ticket</a>'
            );

        } catch (\Throwable $e) {
            DB::rollBack();
            Cache::forget($submissionKey);

            Log::error('Error al crear el ticket', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'error' => 'Hubo un problema al crear tu ticket. Intenta nuevamente.'
            ])->withInput();
        }
    }

    private function sendNotifications(Ticket $ticket): void
    {
        try {
            // Usuario asignado
            if ($ticket->assigned_to_user_id) {
                $assignedUser = User::find($ticket->assigned_to_user_id);

                if ($assignedUser) {
                    $assignedUser->notify(
                        new DataChangeEmailNotification($ticket->toArray())
                    );
                }
            }

            // Admins (cacheado)
            $admins = Cache::remember('ticket_admin_users', 300, function () {
                return User::whereHas('roles', function ($q) {
                    $q->where('title', 'ADMIN');
                })->get();
            });

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AssignedTicketNotification($ticket));
            }

            // Autor
            if (!empty($ticket->author_email)) {
                Notification::route('mail', $ticket->author_email)
                    ->notify(new AssignedTicketNotification($ticket));
            }

        } catch (\Throwable $e) {
            Log::error('Error enviando notificaciones', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function processAttachments(Ticket $ticket, Request $request): void
    {
        foreach ((array) $request->input('attachments', []) as $file) {
            $file = basename($file);
            $path = storage_path('app/tmp/uploads/' . $file);

            if (is_file($path)) {
                $ticket->addMedia($path)->toMediaCollection('attachments');
            } else {
                Log::warning("Archivo adjunto no encontrado: {$path}");
            }
        }
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('comments');

        $ticket->created_at_formatted = $ticket->created_at->format('d-m-Y H:i:s');

        return view('tickets.show', compact('ticket'));
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment_text' => 'required|string',
        ]);

        // ✅ FIX BUG lógica de cerrado
        if ($ticket->status->name === 'CERRADO') {
            $closedAt = $ticket->updated_at ?? now();
            $hoursSinceClosed = now()->diffInHours($closedAt);

            if ($hoursSinceClosed > 12) {
                return back()->withErrors([
                    'error' => 'Ya no puedes agregar comentarios. Han pasado más de 12 horas desde el cierre.'
                ]);
            }
        }

        $comment = $ticket->comments()->create([
            'author_name' => $ticket->author_name,
            'author_email' => $ticket->author_email,
            'comment_text' => $request->comment_text,
        ]);

        $ticket->sendCommentNotification($comment);

        return back()->withStatus(
            'Comentario enviado con éxito. En breve serás atendido por el analista.'
        );
    }
}
