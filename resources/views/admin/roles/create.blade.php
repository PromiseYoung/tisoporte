@extends('layouts.admin')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Título -->
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title" class="form-label">{{ trans('cruds.role.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
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

            <!-- Permisos -->
            <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                <label for="permissions" class="form-label">{{ trans('cruds.role.fields.permissions') }}*
                    <span class="btn btn-info btn-sm select-all" style="cursor: pointer;">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-sm deselect-all" style="cursor: pointer;">{{ trans('global.deselect_all') }}</span>
                </label>
                <select name="permissions[]" id="permissions" class="form-control select2 @error('permissions') is-invalid @enderror" multiple="multiple" required>
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

            <!-- Botón de Guardar -->
            <div class="form-group">
                <button class="btn btn-success btn-lg" type="submit">
                    <i class="fas fa-save"></i> {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
