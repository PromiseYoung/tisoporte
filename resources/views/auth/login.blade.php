@extends('layouts.auth')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #c0ffc5, #abe4ad, #a7f3aa);
            background-size: 600% 600%;
            animation: gradientBG 10s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card-panel {
            max-width: 420px;
            width: 100%;
            padding: 35px 30px;
            border-radius: 15px;
            animation: fadeInUp 0.7s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-img {
            max-width: 180px;
            margin-bottom: 10px;
        }

        .login-title {
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
            font-weight: 600;
        }

        .btn-login {
            border-radius: 30px;
            padding: 12px 30px;
            background: linear-gradient(45deg, #66bb6a, #43a047);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #43a047, #2e7d32);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }

        .input-field input:focus {
            border-bottom: 1px solid #388e3c !important;
            box-shadow: 0 1px 0 0 #388e3c !important;
        }

        .input-field i {
            color: #66bb6a;
        }

        .remember-me {
            font-size: 14px;
        }
    </style>

    <div class="login-container">
        <div class="card-panel z-depth-3 white">
            <div class="center-align">
                <img src="{{ asset('logo/load.png') }}" alt="Logo" class="logo-img">
                <h5 class="login-title teal-text text-darken-3">{{ trans('panel.site_title') }}</h5>
                <p class="grey-text text-darken-1">{{ trans('global.login') }}</p>
            </div>

            @if (session('status'))
                <div class="card-panel green lighten-4 green-text text-darken-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="email" name="email" type="email" class="{{ $errors->has('email') ? 'invalid' : '' }}"
                        value="{{ old('email') }}" required autofocus>
                    <label for="email">{{ trans('global.login_email') }}</label>
                    @if ($errors->has('email'))
                        <span class="helper-text red-text">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">lock</i>
                    <input id="password" name="password" type="password"
                        class="{{ $errors->has('password') ? 'invalid' : '' }}" required>
                    <label for="password">{{ trans('global.login_password') }}</label>
                    @if ($errors->has('password'))
                        <span class="helper-text red-text">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <p class="mb-2 remember-me">
                    <label>
                        <input type="checkbox" name="remember" id="remember" />
                        <span>{{ trans('global.remember_me') }}</span>
                    </label>
                </p>

                <div class="center-align mt-3">
                    <button type="submit" class="btn btn-login waves-effect waves-light">
                        {{ trans('global.login') }}
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="center-align mt-3">
                        <a href="{{ route('password.request') }}" class="grey-text text-darken-1">
                            {{ trans('global.forgot_password') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
