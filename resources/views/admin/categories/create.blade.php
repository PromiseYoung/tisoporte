@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.category.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.category.fields.name_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
                    <label for="color">{{ trans('cruds.category.fields.color') }}</label>
                    <input type="text" id="color" name="color" class="form-control colorpicker"
                        value="{{ old('color', isset($category) ? $category->color : '') }}">
                    @if ($errors->has('color'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('color') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.category.fields.color_helper') }}
                    </small>
                </div>
                <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                    <label for="user_id">{{ trans('cruds.category.fields.user') }}</label>
                    <select name="user_id" id="user_id" class="form-control select2" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.category.fields.user_helper') }}
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

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.colorpicker').colorpicker();
        });
    </script>
@endsection
