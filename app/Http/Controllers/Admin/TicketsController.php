<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Category;
use App\Localidad;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\DataChangeEmailNotification;
use App\Priority;
use App\Status;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {

        // Control de acceso
        if ($request->ajax()) {
            $query = Ticket::with([
                'status',
                'priority',
                'category',
                'assigned_to_user',
                'localidad'
            ])
                ->withCount('comments')
                ->filterTickets($request)
                ->select('tickets.*');
            $table = Datatables::of($query);

            // Definición de columnas y sus valores
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // Definición de acciones para cada fila
            $table->editColumn('actions', function ($row) {
                $viewGate = 'ticket_show';
                $editGate = 'ticket_edit';
                $deleteGate = 'ticket_delete';
                $crudRoutePart = 'tickets';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            // Definición de columnas y sus valores
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('priority_name', function ($row) {
                return $row->priority ? $row->priority->name : '';
            });
            $table->addColumn('priority_color', function ($row) {
                return $row->priority ? $row->priority->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });

            $table->addColumn('localidad_nombre', function ($row) {
                return $row->localidad ? $row->localidad->nombre : '';  // Usamos el nombre de la localidad
            });

            $table->editColumn('author_name', function ($row) {
                return $row->author_name ? $row->author_name : '';
            });

            $table->editColumn('author_email', function ($row) {
                return $row->author_email ? $row->author_email : '';
            });

            $table->addColumn('comments_count', function ($row) {
                return $row->comments->count();
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.tickets.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        // Cargar datos para filtros
        $priorities = Priority::all();
        $statuses = Status::all();
        $categories = Category::all();
        $localidades = Localidad::all();

        return view('admin.tickets.index', compact('priorities', 'statuses', 'categories', 'localidades'));
    }

    public function create()
    {
        abort_if(\Illuminate\Support\Facades\Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::pluck('name', 'id')
            ->prepend(trans('Selecciona el status'), '');

        $priorities = Priority::pluck('name', 'id')
            ->prepend(trans('Elige la prioridad del soporte'), '');

        $categories = Category::pluck('name', 'id')
            ->prepend(trans('Elige la categoria'), '');

        $localidad = Localidad::pluck('nombre', 'id')
            ->prepend(trans('Selecciona una localidad'), '');

        $user = auth()->user();

        $assigned_to_users = User::where(function ($query) use ($user) {

            $query->whereHas('roles', function ($q) {
                $q->whereIn('id', [1, 2]);
            });

            if ($user) {
                $query->orWhere('id', $user->id);
            }

        })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tickets.create', compact(
            'statuses',
            'priorities',
            'categories',
            'assigned_to_users',
            'localidad'
        ));
    }

    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();

        // Intentar crear el ticket y manejar adjuntos
        try {
            $data = $request->all();

            // Si no se asignó manualmente, se asigna el usuario actual
            if (empty($data['assigned_to_user_id'])) {
                $data['assigned_to_user_id'] = auth()->id();
            }

            // Validar que el usuario asignado exista
            if (!empty($data['assigned_to_user_id'])) {
                $userExists = User::where('id', $data['assigned_to_user_id'])->exists();
                if (!$userExists) {
                    throw new \Exception('El usuario asignado no existe.');
                }
            }

            // Crear el ticket
            $ticket = $this->createTicket($data);
            $this->handleAttachments($request, $ticket);
            // Confirmar la transacción
            DB::commit();
            // Notificaciones - procesar de manera más eficiente
            $this->sendNotifications($ticket);



            // CORRECCIÓN: withStatus solo recibe un parámetro
            return redirect()->route('admin.tickets.index')->with('status', 'Ticket creado exitosamente.');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Falla al crear el ticket', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.tickets.index')->withErrors(['error' => 'Hubo un problema al crear el ticket. Intenta nuevamente.']);
        }
    }


    private function sendNotifications(Ticket $ticket): void
    {
        try {
            // Notificar al usuario asignado
            if ($ticket->assigned_to_user_id) {
                $assignedUser = User::find($ticket->assigned_to_user_id);
                if ($assignedUser) {
                    $assignedUser->notify(new DataChangeEmailNotification($ticket->toArray()));
                }
            }

            // Notificar a todos los administradores (con cache para mejor performance)
            $admins = cache()->remember('ticket_admins', 600, function () {
                return User::whereHas('roles', function ($q) {
                    $q->where('title', 'ADMIN');
                })->get();
            });

            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new AssignedTicketNotification($ticket));
            }

            // Notificar al autor del ticket
            if (!empty($ticket->author_email) && filter_var($ticket->author_email, FILTER_VALIDATE_EMAIL)) {
                Notification::route('mail', $ticket->author_email)
                    ->notify(new AssignedTicketNotification($ticket));
            }

        } catch (\Exception $e) {
            Log::error('Error enviando notificaciones: ' . $e->getMessage(), [
                'ticket_id' => $ticket->id,
                'error_trace' => $e->getTraceAsString()
            ]);
            // NO relanzar la excepción para no interrumpir el flujo principal
        }
    }

    private function createTicket($data)
    {
        // creacion del ticket - mantenemos el parámetro como array
        return Ticket::create($data);
    }

    private function handleAttachments($request, $ticket)
    {
        foreach ($request->input('attachments', []) as $file) {
            $ticket->addMedia(storage_path('app/tmp/uploads/' . $file))->toMediaCollection('attachments');
        }
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();

        $assigned_to_users_query = User::whereHas('roles', function ($query) {
            $query->whereIn('id', [1, 2]);
        });

        if ($user) {
            $assigned_to_users_query->orWhere('id', $user->id);
        }

        $data = [
            'statuses' => Status::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'priorities' => Priority::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'categories' => Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'localidad' => Localidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'assigned_to_users' => $assigned_to_users_query->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
        ];

        $ticket->load('status', 'priority', 'category', 'assigned_to_user', 'localidad');

        return view('admin.tickets.edit', array_merge($data, compact('ticket')));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->all());

        if ($ticket->attachments->isNotEmpty()) {
            foreach ($ticket->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $ticket->attachments->pluck('file_name')->toArray();
        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $file = basename($file);
                $ticket->addMedia(storage_path('app/tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.tickets.index');
    }

    public function show(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->load('status', 'priority', 'category', 'assigned_to_user', 'comments', 'localidad');

        return view('admin.tickets.show', compact('ticket'));
    }

    public function destroy(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        $ids = $request->input('ids', []);

        if (!empty($ids)) {
            Ticket::whereIn('id', $ids)->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // Cierra automáticamente el ticket si ha pasado el tiempo límite (por ejemplo, 48 horas desde la última actualización)
    public function storeComment(Request $request, Ticket $ticket)
    {
        $autoCloseHours = 48;
        $closedStatus = cache()->remember('status_closed', 3600, function () {
            return Status::where('name', 'CERRADO')->firstOrFail();
        });
        // Si no está cerrado, verificar inactividad
        if ($ticket->status_id !== $closedStatus->id) {
            $inactiveHours = $ticket->updated_at->diffInHours(now());

            if ($inactiveHours >= $autoCloseHours) {
                $ticket->update([
                    'status_id' => $closedStatus->id
                ]);
            }
        }

        // Refrescar estado
        $ticket->refresh();

        // Bloquear si está cerrado
        if ($ticket->status_id === $closedStatus->id) {
            return back()->withErrors([
                'error' => 'Este ticket fue cerrado automáticamente por inactividad.'
            ]);
        }

        // Validar comentario
        $request->validate([
            'comment_text' => 'required|string',
        ]);

        $this->middleware('throttle:20,1')->only('storeComment');
        // Guardar comentario
        $comment = $ticket->comments()->create([
            'author_name' => auth()->user()->name,
            'author_email' => auth()->user()->email,
            'comment_text' => $request->comment_text,
        ]);

        $ticket->sendCommentNotification($comment);

        return back()->withStatus('Comentario enviado con éxito.');
    }

}
