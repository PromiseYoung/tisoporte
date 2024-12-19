@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Mostrar mensaje de éxito si existe -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card border-0 shadow-lg rounded">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h5 class="mb-0">Ticket #{{ $ticket->id }}</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.created_at') }}</th>
                                    <td>{{ $ticket->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.assigned_to_user') }}</th>
                                    <td>{{ $ticket->assigned_to_user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.title') }}</th>
                                    <td>{{ $ticket->title }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.content') }}</th>
                                    <td>{!! nl2br(e($ticket->content)) !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.attachments') }}</th>
                                    <td>
                                        @foreach ($ticket->attachments as $attachment)
                                            <a href="{{ $attachment->getUrl() }}">{{ $attachment->file_name }}</a><br>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.status') }}</th>
                                    <td>{{ $ticket->status->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.category') }}</th>
                                    <td>{{ $ticket->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.priority') }}</th>
                                    <td>{{ $ticket->priority->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.localidad') }}</th>
                                    <td>{{ $ticket->localidad->nombre ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.author_name') }}</th>
                                    <td>{{ $ticket->author_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.author_email') }}</th>
                                    <td>{{ $ticket->author_email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.comments') }}</th>
                                    <td>
                                        @forelse ($ticket->comments as $comment)
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <p class="font-weight-bold">
                                                        <a
                                                            href="mailto:{{ $comment->author_email }}">{{ $comment->author_name }}</a>
                                                        <span
                                                            class="text-muted">({{ $comment->created_at->format('d-m-Y H:i') }})</span>
                                                    </p>
                                                    <p>{{ $comment->comment_text }}</p>
                                                </div>
                                            </div>
                                            @if (!$loop->last)
                                                <hr>
                                            @endif
                                        @empty
                                            <p>Sin Comentarios</p>
                                        @endforelse
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <form action="{{ route('tickets.storeComment', $ticket->id) }}" method="POST">
                            @csrf

                            <!-- Mostrar el mensaje de error si el ticket está cerrado -->
                            @if ($errors->has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('error') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <!-- Etiqueta y campo de texto para añadir un comentario -->
                                <label for="comment_text">Añadir un comentario</label>

                                <!-- Mostrar el área de texto solo si el ticket no está cerrado -->
                                @if ($ticket->status->name != 'CERRADO')
                                    <textarea class="form-control @error('comment_text') is-invalid @enderror" id="comment_text" name="comment_text"
                                        rows="3" required></textarea>
                                @else
                                    <!-- Mostrar mensaje indicativo si el ticket está cerrado -->
                                    <div class="alert alert-warning" role="alert">
                                        Este ticket está cerrado, por lo que no puedes agregar más comentarios.
                                    </div>
                                @endif

                                <!-- Mensajes de error para el campo de texto si existe -->
                                @error('comment_text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Mostrar el botón solo si el ticket no está cerrado -->
                            @if ($ticket->status->name != 'CERRADO')
                                <div class="text-center">
                                    <button type="submit"
                                        class="btn btn-primary mb-3 rounded-pill">@lang('global.submit')</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
