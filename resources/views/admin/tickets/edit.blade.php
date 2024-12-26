@extends('layouts.admin')
@section('content')

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.ticket.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.tickets.update', [$ticket->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">{{ trans('cruds.ticket.fields.title') }}*</label>
                    <input type="text" id="title" name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', isset($ticket) ? $ticket->title : '') }}" required>
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        {{ trans('cruds.ticket.fields.title_helper') }}
                    </small>
                </div>

                <div class="form-group">
                    <label for="content">{{ trans('cruds.ticket.fields.content') }}</label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror">{{ old('content', isset($ticket) ? $ticket->content : '') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        {{ trans('cruds.ticket.fields.content_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                    <label for="attachments">{{ trans('cruds.ticket.fields.attachments') }}</label>
                    <div class="needsclick dropzone" id="attachments-dropzone"></div>
                    @if ($errors->has('attachments'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('attachments') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.ticket.fields.attachments_helper') }}
                    </small>
                </div>

                <div class="form-group">
                    <label for="status">{{ trans('cruds.ticket.fields.status') }}*</label>
                    <select name="status_id" id="status"
                        class="form-control select2 @error('status_id') is-invalid @enderror" required>
                        @foreach ($statuses as $id => $status)
                            <option value="{{ $id }}"
                                {{ (isset($ticket) && $ticket->status ? $ticket->status->id : old('status_id')) == $id ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="priority">{{ trans('cruds.ticket.fields.priority') }}*</label>
                    <select name="priority_id" id="priority"
                        class="form-control select2 @error('priority_id') is-invalid @enderror" required>
                        @foreach ($priorities as $id => $priority)
                            <option value="{{ $id }}"
                                {{ (isset($ticket) && $ticket->priority ? $ticket->priority->id : old('priority_id')) == $id ? 'selected' : '' }}>
                                {{ $priority }}</option>
                        @endforeach
                    </select>
                    @error('priority_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category">{{ trans('cruds.ticket.fields.category') }}*</label>
                    <select name="category_id" id="category"
                        class="form-control select2 @error('category_id') is-invalid @enderror" required>
                        @foreach ($categories as $id => $category)
                            <option value="{{ $id }}"
                                {{ (isset($ticket) && $ticket->category ? $ticket->category->id : old('category_id')) == $id ? 'selected' : '' }}>
                                {{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="localidad_id">Localidad</label>
                    <select id="localidad_id" name="localidad_id"
                        class="form-control select2 @error('localidad_id') is-invalid @enderror rounded-pill" required>
                        <option selected disabled>Ubicacion de Almacen</option>
                        @foreach ($localidad as $id => $nombre)
                            <option value="{{ $id }}"
                                {{ old('localidad_id', $ticket->localidad_id) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('localidad_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="author_name">{{ trans('cruds.ticket.fields.author_name') }}</label>
                    <input type="text" id="author_name" name="author_name"
                        class="form-control @error('author_name') is-invalid @enderror"
                        value="{{ old('author_name', isset($ticket) ? $ticket->author_name : '') }}"
                        placeholder="Coloca el nombre del usuario a atender" required>
                    @error('author_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        {{ trans('cruds.ticket.fields.author_name_helper') }}
                    </small>
                </div>

                <div class="form-group">
                    <label for="author_email">{{ trans('cruds.ticket.fields.author_email') }}</label>
                    <input type="text" id="author_email" name="author_email"
                        class="form-control @error('author_email') is-invalid @enderror"
                        value="{{ old('author_email', isset($ticket) ? $ticket->author_email : '') }}"
                        placeholder="Correo del Analista TI" required>
                    @error('author_email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        {{ trans('cruds.ticket.fields.author_email_helper') }}
                    </small>
                </div>

                @if (auth()->user()->isAdmin())
                    <div class="form-group">
                        <label for="assigned_to_user">{{ trans('cruds.ticket.fields.assigned_to_user') }}</label>
                        <select name="assigned_to_user_id" id="assigned_to_user"
                            class="form-control select2 @error('assigned_to_user_id') is-invalid @enderror">
                            @foreach ($assigned_to_users as $id => $assigned_to_user)
                                <option value="{{ $id }}"
                                    {{ (isset($ticket) && $ticket->assigned_to_user ? $ticket->assigned_to_user->id : old('assigned_to_user_id')) == $id ? 'selected' : '' }}>
                                    {{ $assigned_to_user }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to_user_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endif

                <div class="mt-3">
                    <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var uploadedAttachmentsMap = {};
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('admin.tickets.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">');
                uploadedAttachmentsMap[file.name] = response.name;
            },
            removedfile: function(file) {
                file.previewElement.remove();
                var name = file.file_name !== undefined ? file.file_name : uploadedAttachmentsMap[file.name];
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove();
            },
            init: function() {
                @if (isset($ticket) && $ticket->attachments)
                    var files = {!! json_encode($ticket->attachments) !!};
                    for (var i in files) {
                        var file = files[i];
                        this.options.addedfile.call(this, file);
                        file.previewElement.classList.add('dz-complete');
                        $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name +
                            '">');
                    }
                @endif
            },
            error: function(file, response) {
                var message = $.type(response) === 'string' ? response : response.errors.file;
                file.previewElement.classList.add('dz-error');
                var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]');
                for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                    var node = _ref[_i];
                    node.textContent = message;
                }
            }
        };
    </script>
@stop
