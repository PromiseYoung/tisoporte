@extends('layouts.admin')
@section('content')
    <div class="card shadow-lg rounded border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.comment.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.comments.update', [$comment->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Ticket Select -->
                <div class="form-group {{ $errors->has('ticket_id') ? 'is-invalid' : '' }}">
                    <label for="ticket" class="form-label">{{ trans('cruds.comment.fields.ticket') }}</label>
                    <select name="ticket_id" id="ticket" class="form-control select2" required>
                        @foreach ($tickets as $id => $ticket)
                            <option value="{{ $id }}"
                                {{ (isset($comment) && $comment->ticket ? $comment->ticket->id : old('ticket_id')) == $id ? 'selected' : '' }}>
                                {{ $ticket }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('ticket_id'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('ticket_id') }}
                        </div>
                    @endif
                </div>

                <!-- Author Name -->
                <div class="form-group {{ $errors->has('author_name') ? 'is-invalid' : '' }}">
                    <label for="author_name" class="form-label">{{ trans('cruds.comment.fields.author_name') }}*</label>
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

                <!-- Author Email -->
                <div class="form-group {{ $errors->has('author_email') ? 'is-invalid' : '' }}">
                    <label for="author_email" class="form-label">{{ trans('cruds.comment.fields.author_email') }}*</label>
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

                <!-- User Select -->
                <div class="form-group {{ $errors->has('user_id') ? 'is-invalid' : '' }}">
                    <label for="user" class="form-label">{{ trans('cruds.comment.fields.user') }}</label>
                    <select name="user_id" id="user" class="form-control select2">
                        @foreach ($users as $id => $user)
                            <option value="{{ $id }}"
                                {{ (isset($comment) && $comment->user ? $comment->user->id : old('user_id')) == $id ? 'selected' : '' }}>
                                {{ $user }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                </div>

                <!-- Comment Text -->
                <div class="form-group {{ $errors->has('comment_text') ? 'is-invalid' : '' }}">
                    <label for="comment_text" class="form-label">{{ trans('cruds.comment.fields.comment_text') }}*</label>
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

                <!-- Submit Button -->
                <div class="form-group text-end">
                    <button class="btn btn-success btn-lg px-4 py-2 rounded-pill" type="submit">
                        <i class="fas fa-save"></i> {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
