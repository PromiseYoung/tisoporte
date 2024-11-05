<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Priority;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;

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
        $categories = Category::all();
        $priorities = Priority::all();
        $users = User::all();
        return view(
            'tickets.create',
            compact('categories', 'priorities', 'users')
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
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'author_name' => 'required',
            'author_email' => 'required|email',
            'category' => 'required|exists:categories,name',
            'priority' => 'required|exists:priorities,name',

        ]);

        // Convertir nombres a IDs
        $category = Category::where('name', $request->category)->first();
        $priority = Priority::where('name', $request->priority)->first();
        //Asociar Analista por la categoria que le corresponde
        $user = User::find($category->user_id);
        

        // Agregar los IDs al request
        $request->request->add([
            'category_id' => $category->id,
            'priority_id' => $priority->id,
            'assigned_to_user_id' => optional($user)->id,
            'status_id' => 1,
        ]);

        $ticket = Ticket::create($request->all());

        foreach ($request->input('attachments', []) as $file) {
            $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }
        return redirect()->back()->withStatus('Tu ticket ha sido enviado, nos pondremos en contacto contigo. Puedes ver el estado del ticket <a href="' . route('tickets.show', $ticket->id) . '">Clic</a>');
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

        return view('tickets.show', compact('ticket'));
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate(['comment_text' => 'required']);

        $comment = $ticket->comments()->create([
            'author_name' => $ticket->author_name,
            'author_email' => $ticket->author_email,
            'comment_text' => $request->comment_text,
        ]);

        $ticket->sendCommentNotification($comment);

        return redirect()->back()->withStatus('Comentario agregado, el administrador observará el seguimiento de tu soporte. Gracias por tu comprensión.');
    }
}
