@extends('layouts.admin')
@section('content')
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-gradient-success">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>&nbsp; {{ trans('global.create') }}
                {{ trans('cruds.category.title_singular') }}
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">{{ trans('cruds.category.fields.name') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ trans('cruds.category.fields.name_helper') }}</div>
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label fw-semibold">{{ trans('cruds.category.fields.color') }}</label>
                    <input type="text" id="color" name="color"
                        class="form-control colorpicker @error('color') is-invalid @enderror"
                        value="{{ old('color', isset($category) ? $category->color : '') }}">
                    @error('color')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ trans('cruds.category.fields.color_helper') }}</div>
                </div>

                <div class="mb-4">
                    <label for="user_id" class="form-label fw-semibold">{{ trans('cruds.category.fields.user') }} <span
                            class="text-danger">*</span></label>
                    <select name="user_id" id="user_id" class="form-select select2 @error('user_id') is-invalid @enderror"
                        required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ trans('cruds.category.fields.user_helper') }}</div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>&nbsp;{{ trans('global.save') }}
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
