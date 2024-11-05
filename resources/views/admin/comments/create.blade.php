@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.comment.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.comments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group {{ $errors->has('ticket_id') ? 'has-error' : '' }}">
                    <label for="ticket">{{ trans('cruds.comment.fields.ticket') }}</label>
                    <select name="ticket_id" id="ticket" class="form-control select2" required>
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach ($tickets as $id => $ticket)
                            <option value="{{ $id }}"
                                {{ (isset($comment) && $comment->ticket ? $comment->ticket->id : old('ticket_id')) == $id ? 'selected' : '' }}>
                                {{ $ticket }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('ticket_id'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('ticket_id') }}
                        </div>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('author_name') ? 'has-error' : '' }}">
                    <label for="author_name">{{ trans('cruds.comment.fields.author_name') }}*</label>
                    <input type="text" id="author_name" name="author_name" class="form-control"
                        value="{{ old('author_name', isset($comment) ? $comment->author_name : '') }}" required>
                    @if ($errors->has('author_name'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('author_name') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.comment.fields.author_name_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('author_email') ? 'has-error' : '' }}">
                    <label for="author_email">{{ trans('cruds.comment.fields.author_email') }}*</label>
                    <input type="email" id="author_email" name="author_email" class="form-control"
                        value="{{ old('author_email', isset($comment) ? $comment->author_email : '') }}" required>
                    @if ($errors->has('author_email'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('author_email') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.comment.fields.author_email_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                    <label for="user">{{ trans('cruds.comment.fields.user') }}</label>
                    <select name="user_id" id="user" class="form-control select2">
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach ($users as $id => $user)
                            <option value="{{ $id }}"
                                {{ (isset($comment) && $comment->user ? $comment->user->id : old('user_id')) == $id ? 'selected' : '' }}>
                                {{ $user }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('comment_text') ? 'has-error' : '' }}">
                    <label for="comment_text">{{ trans('cruds.comment.fields.comment_text') }}*</label>
                    <textarea id="comment_text" name="comment_text" class="form-control" required>{{ old('comment_text', isset($comment) ? $comment->comment_text : '') }}</textarea>
                    @if ($errors->has('comment_text'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('comment_text') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.comment.fields.comment_text_helper') }}
                    </small>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
