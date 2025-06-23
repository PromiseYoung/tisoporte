@extends('layouts.auth')

@section('content')
    <style>
        .reset-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7);
            padding: 20px;
        }

        .card-reset {
            max-width: 480px;
            width: 100%;
            border-radius: 12px;
        }

        .input-field input:focus {
            border-bottom: 1px solid #00796b !important;
            box-shadow: 0 1px 0 0 #00796b !important;
        }

        .btn-custom {
            border-radius: 30px;
            width: 100%;
        }

        .icon-prefix {
            color: #4db6ac;
        }
    </style>

    <div class="reset-container">
        <div class="card white card-reset z-depth-3">
            <div class="card-content">
                <div class="center-align mb-3">
                    <h5 class="teal-text text-darken-2">{{ trans('panel.site_title') }}</h5>
                    <p class="grey-text text-darken-1">{{ trans('global.reset_password') }}</p>
                </div>

                <form method="POST" action="{{ route('password.request') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-field">
                        <i class="material-icons prefix icon-prefix">email</i>
                        <input id="email" type="email" name="email"
                            class="{{ $errors->has('email') ? 'invalid' : '' }}" required
                            value="{{ $email ?? old('email') }}">
                        <label for="email">{{ trans('global.login_email') }}</label>
                        @if ($errors->has('email'))
                            <span class="helper-text red-text">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix icon-prefix">lock</i>
                        <input id="password" type="password" name="password"
                            class="{{ $errors->has('password') ? 'invalid' : '' }}" required>
                        <label for="password">{{ trans('global.login_password') }}</label>
                        @if ($errors->has('password'))
                            <span class="helper-text red-text">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="input-field">
                        <i class="material-icons prefix icon-prefix">lock_outline</i>
                        <input id="password-confirm" type="password" name="password_confirmation" required>
                        <label for="password-confirm">{{ trans('global.login_password_confirmation') }}</label>
                    </div>

                    <div class="center-align mt-4">
                        <button type="submit" class="btn teal darken-2 btn-custom waves-effect waves-light">
                            {{ trans('global.reset_password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
