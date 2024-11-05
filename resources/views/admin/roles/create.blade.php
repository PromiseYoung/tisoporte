@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.role.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" 
                       value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <small class="form-text text-muted">
                    {{ trans('cruds.role.fields.title_helper') }}
                </small>
            </div>

            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                <label for="permissions">{{ trans('cruds.role.fields.permissions') }}*
                    <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span>
                </label>
                <select name="permissions[]" id="permissions" class="form-control select2" multiple="multiple" required>
                    @foreach($permissions as $id => $permission)
                        <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($role) && $role->permissions->contains($id)) ? 'selected' : '' }}>
                            {{ $permission }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('permissions'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('permissions') }}
                    </div>
                @endif
                <small class="form-text text-muted">
                    {{ trans('cruds.role.fields.permissions_helper') }}
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
