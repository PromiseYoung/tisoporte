@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.ticket.title') }}</h5>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.id') }}</th>
                            <td>{{ $ticket->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.created_at') }}</th>
                            <td>{{ $ticket->created_at->locale('es')->format('d-m-Y H:i:s A') }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.title') }}</th>
                            <td>{{ $ticket->title }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.content') }}</th>
                            <td>{!! $ticket->content !!}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.attachments') }}</th>
                            <td>
                                @foreach ($ticket->attachments as $attachment)
                                    <a href="{{ $attachment->getUrl() }}" target="_blank"
                                        class="text-primary">{{ $attachment->file_name }}</a><br />
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.status') }}</th>
                            <td>{{ $ticket->status->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.priority') }}</th>
                            <td>{{ $ticket->priority->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.category') }}</th>
                            <td>{{ $ticket->category->name ?? '' }}</td>
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
                            <th>{{ trans('cruds.ticket.fields.assigned_to_user') }}</th>
                            <td>{{ $ticket->assigned_to_user->name ?? '' }}</td>
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
                                                <small
                                                    class="text-muted">({{ $comment->created_at->locale('es')->format('d-m-Y H:i:s A') }})</small>
                                            </p>
                                            <p>{{ $comment->comment_text }}</p>
                                        </div>
                                    </div>
                                    <hr />
                                @empty
                                    <p class="text-muted">Sin Comentarios</p>
                                @endforelse
                                <form action="{{ route('admin.tickets.storeComment', $ticket->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="comment_text">Añadir un comentario</label>
                                        <textarea class="form-control" id="comment_text" name="comment_text" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">@lang('global.submit')</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <div class="btn-group" role="group">
                    <a class="btn btn-info" href="{{ route('admin.tickets.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-secondary">
                        @lang('global.edit') @lang('cruds.ticket.title_singular')
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
