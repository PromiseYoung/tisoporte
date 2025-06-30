<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Authors;
use App\Category;
use App\Localidad;
use App\Priority;
use App\Status;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Gate;

class TicketsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ticket::with(['status', 'priority', 'category', 'assigned_to_user', 'comments'])
                ->filterTickets($request)
                ->select(sprintf('%s.*', (new Ticket)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

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
                return $row->author ? $row->author->name : '';
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

            $table->rawColumns(['actions', 'placeholder', 'status', 'priority', 'category', 'assigned_to_user', 'localidad']);

            return $table->make(true);
        }

        $priorities = Priority::all();
        $statuses = Status::all();
        $categories = Category::all();
        $localidades = Localidad::all();

        return view('admin.tickets.index', compact('priorities', 'statuses', 'categories', 'localidades'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('Selecciona el status'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('Elige la prioridad del soporte'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('Elige la categoria'), '');

        $authors = Authors::all()->pluck('name', 'id')->prepend('Seleccione un autor', '');

        $localidad = Localidad::all()->pluck('nombre', 'id')->prepend(trans('Selecciona una localidad'), '');

        $user = auth()->user();

        $assigned_to_users_query = User::whereHas('roles', function ($query) {
            $query->whereIn('id', [1, 2]);
        });

        if ($user) {
            $assigned_to_users_query->orWhere('id', $user->id);
        }

        $assigned_to_users = $assigned_to_users_query->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.tickets.create', compact('statuses', 'priorities', 'categories', 'assigned_to_users', 'localidad', 'authors'));
    }

    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            // Si no se asignó manualmente, se asigna el usuario actual
            if (empty($data['assigned_to_user_id'])) {
                $data['assigned_to_user_id'] = auth()->id();
            }

            $ticket = Ticket::create($data);
            $this->handleAttachments($request, $ticket);

            DB::commit();

            return redirect()->route('admin.tickets.index')->withStatus('status', 'Ticket creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al crear el ticket: ' . $e->getMessage());

            return redirect()->route('admin.tickets.index')->withErrors(['error' => 'Hubo un problema al crear el ticket. Intenta nuevamente.']);
        }
    }

    private function createTicket($request)
    {
        return Ticket::create($request->all());
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
            'authors' => Authors::pluck('name', 'id'),
            'localidad' => Localidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'assigned_to_users' => $assigned_to_users_query->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
        ];

        $ticket->load('status', 'priority', 'category', 'assigned_to_user', 'localidad');

        return view('admin.tickets.edit', array_merge($data, compact('ticket')));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->all());

        if (count($ticket->attachments) > 0) {
            foreach ($ticket->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $ticket->attachments->pluck('file_name')->toArray();
        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
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
        Ticket::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // Cierra automáticamente el ticket si ha pasado el tiempo límite (por ejemplo, 48 horas desde la última actualización)
    public function storeComment(Request $request, Ticket $ticket)
    {
        $autoCloseHours = 48;

        // Cerrar automáticamente si ha pasado el tiempo de inactividad
        if ($ticket->status->name !== 'CERRADO') {
            $lastUpdated = $ticket->updated_at ?? $ticket->created_at;
            if ($lastUpdated && now()->diffInHours($lastUpdated) >= $autoCloseHours) {
                $closedStatus = Status::where('name', 'CERRADO')->first();
                if ($closedStatus) {
                    $ticket->status_id = $closedStatus->id;
                    $ticket->save();
                }
            }
        }

        // Verifica si el ticket ya fue cerrado (por inactividad u otra causa)
        $ticket->refresh();
        if ($ticket->status->name === 'CERRADO') {
            return redirect()->back()->withErrors([
                'error' => 'Este ticket ya ha sido cerrado por inactividad. Por favor, crea uno nuevo si necesitas continuar con el soporte.'
            ]);
        }

        // Validar el comentario
        $request->validate([
            'comment_text' => 'required',
        ]);

        // Guardar comentario
        $user = auth()->user();
        $comment = $ticket->comments()->create([
            'author_name' => $user->author_name ?? $user->name,
            'author_email' => $user->email ?? $user->author_email,
            'comment_text' => $request->comment_text,
        ]);

        // Notificar al autor del ticket
        $ticket->sendCommentNotification($comment);

        // Mensaje de éxito
        return redirect()->back()->withStatus('Comentario enviado con éxito. El ticket permanece abierto.');
    }

}
