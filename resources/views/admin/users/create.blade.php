@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.user.fields.name_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                    @if ($errors->has('email'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.user.fields.email_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.user.fields.password_helper') }}
                    </small>
                </div>

                <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                    <label for="roles">{{ trans('cruds.user.fields.roles') }}*
                        <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span>
                    </label>
                    <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                        @foreach ($roles as $id => $role)
                            <option value="{{ $id }}"
                                {{ in_array($id, old('roles', [])) || (isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('roles'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('roles') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">
                        {{ trans('cruds.user.fields.roles_helper') }}
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
