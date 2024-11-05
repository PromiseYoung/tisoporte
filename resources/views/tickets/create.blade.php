@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {!! session('status') !!}
                    </div>
                @endif

                <div class="card border-0 shadow-lg rounded">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h5 class="mb-0">Crear Ticket</h5>
                    </div>

                    <div class="card-body text-dark p-4">
                        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="author_name">Nombre del solicitante</label>
                                <input id="author_name" type="text"
                                    class="form-control @error('author_name') is-invalid @enderror rounded-pill"
                                    name="author_name" value="{{ old('author_name') }}" required autocomplete="name"
                                    autofocus>
                                @error('author_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="author_email">Tu Correo Electrónico</label>
                                <input id="author_email" type="email"
                                    class="form-control @error('author_email') is-invalid @enderror rounded-pill"
                                    name="author_email" value="{{ old('author_email') }}" required autocomplete="email"
                                    placeholder="ejemplo@load.com.mx">
                                @error('author_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="title">@lang('cruds.ticket.fields.title')</label>
                                <input id="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror rounded-pill" name="title"
                                    value="{{ old('title') }}" required autocomplete="title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">@lang('cruds.ticket.fields.content')</label>
                                <textarea class="form-control @error('content') is-invalid @enderror rounded" id="content" name="content"
                                    rows="3" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category">Categoría</label>
                                <select id="category" name="category"
                                    class="form-control select2 @error('category') is-invalid @enderror rounded-pill"
                                    required>
                                    <option selected disabled>Elige la Categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ old('category') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="priority">Prioridad</label>
                                <select id="priority" name="priority"
                                    class="form-control @error('priority') is-invalid @enderror rounded-pill" required>
                                    <option selected disabled>Grado de tu solicitud</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->name }}"
                                            {{ old('priority') == $priority->name ? 'selected' : '' }}>
                                            {{ $priority->name }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="attachments">{{ trans('cruds.ticket.fields.attachments') }}</label>
                                <div class="needsclick dropzone @error('attachments') is-invalid @enderror"
                                    id="attachments-dropzone"></div>
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success rounded-pill">
                                    @lang('global.submit')
                                </button>
                                <footer class="text-center mt-5">
                                    <p>&copy; {{ date('Y') }} Logística y Administración. S.A de C.V</p>
                                </footer>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('tickets.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedAttachmentsMap[file.name]
                }
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($ticket) && $ticket->attachments)
                    var files =
                        {!! json_encode($ticket->attachments) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name +
                            '">')
                    }
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }
                return _results
            }
        }
    </script>
@stop
