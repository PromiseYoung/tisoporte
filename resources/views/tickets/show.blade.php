@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Mostrar mensaje de éxito si existe -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h5 class="mb-0">Ticket #{{ $ticket->id }}</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover table-borderless align-middle">
                            <tbody class="table-group-divider">
                                <tr class="bg-white border-bottom">
                                    <th scope="row" class="text-muted w-25">{{ trans('cruds.ticket.fields.created_at') }}
                                    </th>
                                    <td class="fw-semibold">{{ $ticket->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr class="bg-white border-bottom">
                                    <th scope="row" class="text-muted">
                                        {{ trans('cruds.ticket.fields.assigned_to_user') }}</th>
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
                                        @forelse ($ticket->attachments as $attachment)
                                            <a href="{{ $attachment->getUrl() }}" class="text-decoration-none">
                                                <i class="fas fa-paperclip"></i> {{ $attachment->file_name }}
                                            </a><br>
                                        @empty
                                            <span class="text-muted">Sin adjuntos</span>
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.status') }}</th>
                                    <td>
                                        <span
                                            class="badge bg-{{ $ticket->status->name == 'CERRADO' ? 'danger' : 'success' }}">
                                            {{ $ticket->status->name ?? '' }}
                                        </span>
                                    </td>
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

                                    {{-- $ticket->author->name --}}
                                    <td>{{ $ticket->author_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.ticket.fields.author_email') }}</th>
                                    <td>{{ $ticket->author_email }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 class="mt-4">Comentarios</h5>
                        <div class="mb-4">
                            @forelse ($ticket->comments as $comment)
                                <div class="border rounded-3 p-3 mb-3">
                                    <p class="mb-1 fw-bold">
                                        <a href="mailto:{{ $comment->author_email }}"
                                            class="text-decoration-none text-dark">
                                            {{ $comment->author_name }}
                                        </a>
                                        <span class="text-muted">({{ $comment->created_at->format('d-m-Y H:i') }})</span>
                                    </p>
                                    <p class="mb-0">{{ $comment->comment_text }}</p>
                                </div>
                            @empty
                                <p class="text-muted">Sin Comentarios</p>
                            @endforelse
                        </div>

                        <form action="{{ route('tickets.storeComment', $ticket->id) }}" method="POST">
                            @csrf

                            @if ($errors->has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('error') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="comment_text" class="form-label">Añadir un comentario</label>
                                @if ($ticket->status->name != 'CERRADO')
                                    <textarea class="form-control @error('comment_text') is-invalid @enderror" id="comment_text" name="comment_text"
                                        rows="3" required></textarea>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        Este ticket está cerrado, por lo que no puedes agregar más comentarios.
                                    </div>
                                @endif
                                @error('comment_text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if ($ticket->status->name != 'CERRADO')
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success rounded-pill">
                                        @lang('global.submit')
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
