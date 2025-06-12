<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Localidad;
use App\Category;
use App\Authors;
use App\Priority;
use App\Status;
use App\Ticket;
use App\User;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ticket::with(['status', 'priority', 'category', 'assigned_to_user', 'comments', 'localidad'])
                ->filterTickets($request)
                ->select(sprintf('%s.*', (new Ticket)->table));

            $table = Datatables::of($query)
                ->addColumn('placeholder', '&nbsp;')
                ->addColumn('actions', function ($row) {
                    return view('partials.datatablesActions', [
                        'viewGate' => 'ticket_show',
                        'editGate' => 'ticket_edit',
                        'deleteGate' => 'ticket_delete',
                        'crudRoutePart' => 'tickets',
                        'row' => $row,
                    ]);
                })
                ->editColumn('id', fn($row) => $row->id ?? "")
                ->editColumn('title', fn($row) => $row->title ?? "")
                ->addColumn('status_name', fn($row) => $row->status->name ?? '')
                ->addColumn('status_color', fn($row) => $row->status->color ?? '#000000')
                ->addColumn('priority_name', fn($row) => $row->priority->name ?? '')
                ->addColumn('priority_color', fn($row) => $row->priority->color ?? '#000000')
                ->addColumn('category_name', fn($row) => $row->category->name ?? '')
                ->addColumn('category_color', fn($row) => $row->category->color ?? '#000000')
                ->addColumn('localidad_nombre', fn($row) => $row->localidad->nombre ?? '')
                ->addColumn('author_name', fn($row) => $row->author->name ?? '')
                ->editColumn('author_email', fn($row) => $row->author_email ?? "")
                ->addColumn('assigned_to_user_name', fn($row) => $row->assigned_to_user->name ?? '')
                ->addColumn('comments_count', fn($row) => $row->comments->count())
                ->addColumn('view_link', fn($row) => route('admin.tickets.show', $row->id))
                ->rawColumns(['actions', 'placeholder', 'status', 'priority', 'category', 'assigned_to_user', 'localidad', 'author']);

            return $table->make(true);
        }

        return view('admin.tickets.index', [
            'priorities' => Priority::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            'localidades' => Localidad::all(),
            'authors' => Authors::all(),
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = [
            'statuses' => Status::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'priorities' => Priority::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'categories' => Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'authors' => Authors::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'localidad' => Localidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'assigned_to_users' => User::whereHas('roles', function ($query) {
                $query->whereIn('id', [1, 2]);
            })
                ->orWhere('id', auth()->user()->id)
                ->pluck('name', 'id')
                ->prepend(trans('global.pleaseSelect'), ''),
        ];

        return view('admin.tickets.create', $data);
    }

    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();

        try {
            $ticket = $this->createTicket($request);
            $this->handleAttachments($request, $ticket);

            DB::commit();

            return redirect()->route('admin.tickets.index')->with('status', 'Ticket creado exitosamente.');
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

        $data = [
            'statuses' => Status::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'priorities' => Priority::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'categories' => Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'authors' => Authors::pluck('name', 'id'),
            'localidad' => Localidad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), ''),
            'assigned_to_users' => User::whereHas('roles', function ($query) {
                $query->whereIn('id', [1, 2]);
            })
                ->orWhere('id', auth()->user()->id)
                ->pluck('name', 'id')
                ->prepend(trans('global.pleaseSelect'), ''),
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

    public function storeComment(Request $request, Ticket $ticket)
    {
        // Verificar si el ticket está cerrado
        if ($ticket->status->name === 'CERRADO') {
            return redirect()->back()->withErrors(['error' => 'No puedes agregar comentarios a un ticket cerrado.']);
        }
        $request->validate([
            'comment_text' => 'required'
        ]);
        $user = auth()->user();
        $comment = $ticket->comments()->create([
            'author_name' => $user->name,
            'author_email' => $user->email,
            'user_id' => $user->id,
            'comment_text' => $request->comment_text
        ]);
        $ticket->sendCommentNotification($comment);
        return redirect()->back()->withStatus('Comentario enviado al usuario con exito!');
    }
}
