@extends('layouts.auth')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #a5d6a7);
            background-size: 200% 200%;
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

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            padding: 40px 35px;
            border-radius: 15px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            max-width: 160px;
            margin-bottom: 20px;
        }

        .login-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .btn-login {
            background: linear-gradient(45deg, #26a69a, #2e7d32);
            padding: 12px;
            border-radius: 30px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #2e7d32, #1b5e20);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .input-field i {
            color: #26a69a;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>

    <div class="login-wrapper">
        <div class="card white login-card z-depth-3">
            <div class="center-align">
                <img src="{{ asset('logo/load.png') }}" alt="Logo" class="logo">
                <h5 class="login-title teal-text text-darken-3">{{ trans('panel.site_title') }}</h5>
                <p class="grey-text">{{ trans('global.login') }}</p>
            </div>

            @if (session('status'))
                <div class="card-panel green lighten-4 green-text text-darken-4 mt-2">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-field">
                    <i class="material-icons prefix">email</i>
                    <input id="email" name="email" type="email"
                        class="{{ $errors->has('email') ? 'invalid' : '' }}"
                        value="{{ old('email') }}" required autofocus>
                    <label for="email">{{ trans('global.login_email') }}</label>
                    @error('email')
                        <span class="helper-text red-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-field">
                    <i class="material-icons prefix">lock</i>
                    <input id="password" name="password" type="password"
                        class="{{ $errors->has('password') ? 'invalid' : '' }}" required>
                    <label for="password">{{ trans('global.login_password') }}</label>
                    @error('password')
                        <span class="helper-text red-text">{{ $message }}</span>
                    @enderror
                </div>

                <p class="mb-2">
                    <label>
                        <input type="checkbox" name="remember" id="remember" />
                        <span>{{ trans('global.remember_me') }}</span>
                    </label>
                </p>

                <button type="submit" class="btn btn-login waves-effect waves-light mt-3">
                    {{ trans('global.login') }}
                </button>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="grey-text text-darken-1 forgot-password center-align">
                        {{ trans('global.forgot_password') }}
                    </a>
                @endif
            </form>
        </div>
    </div>
@endsection
