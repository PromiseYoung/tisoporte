@extends('layouts.admin')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.permissions.update', [$permission->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title" class="form-label">{{ trans('cruds.permission.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', isset($permission) ? $permission->title : '') }}" required>

                @if($errors->has('title'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('title') }}
                    </div>
                @endif

                <small class="form-text text-muted">
                    {{ trans('cruds.permission.fields.title_helper') }}
                </small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
