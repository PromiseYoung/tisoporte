@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center dark-mode-header">
            <h5 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.ticket.title') }}</h5>
            <a class="btn btn-light btn-sm dark-mode-btn" href="{{ route('admin.tickets.index') }}">
                <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div class="card-body dark-mode-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show dark-mode-alert" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped dark-mode-table">
                    <tbody>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.id') }}</th>
                            <td class="dark-mode-td">{{ $ticket->id }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.created_at') }}</th>
                            <td class="dark-mode-td">{{ $ticket->created_at->locale('es')->format('d-m-Y H:i:s A') }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.title') }}</th>
                            <td class="dark-mode-td">{{ $ticket->title }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.content') }}</th>
                            <td class="dark-mode-td">{!! $ticket->content !!}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.attachments') }}</th>
                            <td class="dark-mode-td">
                                @foreach ($ticket->attachments as $attachment)
                                    <a href="{{ $attachment->getUrl() }}" target="_blank"
                                        class="text-primary d-block dark-mode-link"><i class="fas fa-paperclip"></i>
                                        {{ $attachment->file_name }}</a>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.status') }}</th>
                            <td class="dark-mode-td"><span
                                    class="badge bg-info dark-mode-badge">{{ $ticket->status->name ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.priority') }}</th>
                            <td class="dark-mode-td"><span
                                    class="badge bg-warning dark-mode-badge">{{ $ticket->priority->name ?? '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.category') }}</th>
                            <td class="dark-mode-td">{{ $ticket->category->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.localidad') }}</th>
                            <td class="dark-mode-td">{{ $ticket->localidad->nombre ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.author_name') }}</th>
                            <td class="dark-mode-td">{{ $ticket->author_name ?? ($ticket->author->name ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.author_email') }}</th>
                            <td class="dark-mode-td">{{ $ticket->author_email }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.assigned_to_user') }}</th>
                            <td class="dark-mode-td">{{ $ticket->assigned_to_user->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th">{{ trans('cruds.ticket.fields.comments') }}</th>
                            <td class="dark-mode-td">
                                @forelse ($ticket->comments as $comment)
                                    <div class="row mb-3 dark-mode-comment">
                                        <div class="col">
                                            <p class="fw-bold mb-1">
                                                <a href="mailto:{{ $comment->author_email }}"
                                                    class="dark-mode-link">{{ $comment->author_name ?? ($comment->author->name ?? 'N/A') }}</a>
                                                <small
                                                    class="text-muted">({{ $comment->created_at->locale('es')->format('d-m-Y H:i:s A') }})</small>
                                            </p>
                                            <p class="mb-0">{{ $comment->comment_text }}</p>
                                        </div>
                                    </div>
                                    <hr class="dark-mode-hr" />
                                @empty
                                    <p class="text-muted">Sin Comentarios</p>
                                @endforelse
                                <form action="{{ route('admin.tickets.storeComment', $ticket->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comment_text" class="form-label">Escribe un comentario</label>
                                        <textarea class="form-control dark-mode-textarea" id="comment_text" name="comment_text" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 dark-mode-btn">
                                        <i class="fas fa-paper-plane"></i> @lang('global.submit')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-secondary me-2 dark-mode-btn">
                    <i class="fas fa-edit"></i> @lang('global.edit') @lang('cruds.ticket.title_singular')
                </a>
            </div>
        </div>
    </div>
@endsection
