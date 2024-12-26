@extends('layouts.auth')

@section('content')
    <div class="row justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4 text-primary">{{ trans('panel.site_title') }}</h1>

                    <p class="text-muted text-center mb-4">{{ trans('global.reset_password') }}</p>

                    <form method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <input name="token" value="{{ $token }}" type="hidden">

                        <div class="form-group mb-4">
                            <label for="email" class="form-label">{{ trans('global.login_email') }}</label>
                            <input id="email" type="email" name="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}"
                                value="{{ $email ?? old('email') }}">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">{{ trans('global.login_password') }}</label>
                            <input id="password" type="password" name="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                placeholder="{{ trans('global.login_password') }}">

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm"
                                class="form-label">{{ trans('global.login_password_confirmation') }}</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control"
                                required placeholder="{{ trans('global.login_password_confirmation') }}">
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                {{ trans('global.reset_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
