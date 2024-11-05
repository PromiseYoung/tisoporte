@extends('layouts.auth')
@section('content')
    <div class="row justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="card border-0 shadow-lg rounded-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4 text-primary">{{ trans('panel.site_title') }}</h1>

                    <p class="text-muted text-center mb-4">{{ trans('global.reset_password') }}</p>

                    @if (session('status'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group mb-4">
                            <input id="email" type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required
                                autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}"
                                value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                {{ trans('global.send_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
