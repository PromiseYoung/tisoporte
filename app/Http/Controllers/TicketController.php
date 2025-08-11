<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Category;
use App\Localidad;
use App\Priority;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Incluir el facade DB
use Illuminate\Support\Str;

// Para generar UUID si es necesario

class TicketController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $priorities = Priority::select('id', 'name')->get();
        $localidades = Localidad::select('id', 'nombre')->get(); // Obtener solo los campos necesarios
        $users = User::select('id', 'name')->get(); // Obtener solo los campos necesarios

        return response()->view(
            'tickets.create',
            compact('categories', 'priorities', 'users', 'localidades')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'author_name' => 'required|string',
            'author_email' => 'required|email',
            'category' => 'required|exists:categories,name',
            'priority' => 'required|exists:priorities,name',
            'localidad' => 'required|exists:localidades,nombre'
        ]);

        DB::beginTransaction();

        try {
            // Buscar registros asociados
            $category = Category::where('name', $request->category)->first();
            $priority = Priority::where('name', $request->priority)->first();
            $localidad = Localidad::where('nombre', $request->localidad)->first();

            // Log para depuración
            \Log::debug('Datos encontrados en store():', [
                'category' => $category,
                'priority' => $priority,
                'localidad' => $localidad,
                'request_category' => $request->category
            ]);

            // Verificar que existan
            if (!$category || !$priority || !$localidad) {
                DB::rollBack();
                return response(
                    redirect()->back()->withErrors([
                        'error' => 'No se encontró la categoría, prioridad o localidad especificada.'
                    ])
                );
            }

            // Buscar usuario asignado según categoría
            $user = null;
            if ($category && isset($category->user_id) && $category->user_id) {
                $user = User::find($category->user_id);
            }

            // Agregar campos adicionales al request
            $request->merge([
                'category_id' => $category->id,
                'priority_id' => $priority->id,
                'localidad_id' => $localidad->id,
                'assigned_to_user_id' => optional($user)->id,
                'status_id' => 1,
                'id' => (string) Str::uuid(),
            ]);

            // Crear ticket
            $ticket = Ticket::create($request->all());

            // Subir archivos adjuntos (si existen)
            foreach ($request->input('attachments', []) as $file) {
                $ticket->addMedia(storage_path('app/tmp/uploads/' . $file))->toMediaCollection('attachments');
            }

            DB::commit();

            return redirect()->back()->withStatus(
                'Tu ticket ha sido enviado, nos pondremos en contacto contigo.
            Puedes ver el estado de la solicitud de tu ticket <a href="' . route('tickets.show', $ticket->id) . '">Ver Ticket</a>'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear el ticket: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response(
                redirect()->back()->withErrors([
                    'error' => 'Hubo un problema al crear tu ticket. Intenta nuevamente.'
                ])
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('comments');

        $ticket->created_at = $ticket->created_at->format('d-m-Y H:i:s');

        return response()->view('tickets.show', compact('ticket'));
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        // Validar el texto del comentario
        $request->validate([
            'comment_text' => 'required',
        ]);

        // Verificar si el ticket está cerrado y cuánto tiempo ha pasado
        if ($ticket->status->name === 'CERRADO') {
            $closedAt = $ticket->updated_at ?? now(); // asumiendo que se cierra con update
            $hoursSinceClosed = now()->diffInHours($closedAt);

            if ($hoursSinceClosed > 12) {
                return redirect()->back()->withErrors([
                    'error' => 'Ya no puedes agregar comentarios. Han pasado más de 12 horas desde el cierre del ticket.',
                ]);
            }

            return redirect()->back()->withErrors([
                'error' => 'Este ticket ha sido cerrado. Si necesitas más ayuda, por favor crea uno nuevo.',
            ]);
        }

        // Crear el comentario asociado al ticket
        $comment = $ticket->comments()->create([
            'author_name' => $ticket->author_name,
            'author_email' => $ticket->author_email,
            'comment_text' => $request->comment_text,
        ]);

        // Enviar notificación al autor del ticket
        $ticket->sendCommentNotification($comment);

        return redirect()->back()->withStatus(
            'Comentario enviado con éxito. En breve serás atendido por el analista asignado.'
        );
    }

}
