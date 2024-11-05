@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.permission.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.permissions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.permission.fields.title') }}*</label>
                    <input type="text" id="title" name="title" class="form-control"
                        value="{{ old('title', isset($permission) ? $permission->title : '') }}" required>
                    @if ($errors->has('title'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.permission.fields.title_helper') }}
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
