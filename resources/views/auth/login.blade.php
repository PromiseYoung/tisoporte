@extends('layouts.auth')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mx-4 shadow-lg borde-0" style="border-radius: 3px;">
                <div class="card-body p-4 d-flex flex-column align-items-center">
                    <div class="text-center mb-4">
                        <img src="{{ asset('logo/load.png') }}" alt="logo" class="img-fluid mb-3" style="max-width: 150px;">
                        <h1 class="h4 text-dark">{{ trans('panel.site_title') }}</h1>
                        <p class="text-muted">{{ trans('global.login') }}</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="w-100">
                        @csrf

                        <div class="form-group mb-3">
                            <input id="email" name="email" type="text"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}"
                                value="{{ old('email', null) }}" style="border: 1px solid #dbdbdb; border-radius: 5px;">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <input id="password" name="password" type="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                placeholder="{{ trans('global.login_password') }}"
                                style="border: 1px solid #dbdbdb; border-radius: 5px;">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" />
                            <label class="form-check-label" for="remember" style="font-size: 14px;">
                                {{ trans('global.remember_me') }}
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-block mb-3" style="border-radius: 5px;">
                            {{ trans('global.login') }}
                        </button>

                        <div class="text-center mb-3">
                            @if (Route::has('password.request'))
                                <a class="text-muted" href="{{ route('password.request') }}" style="font-size: 14px;">
                                    {{ trans('global.forgot_password') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
