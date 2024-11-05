@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="alert alert-warning text-center" role="alert">
            <h1 class="display-4">¡Oops! La página ha expirado.</h1>
            <p class="lead">Parece que has perdido la sesión. Por favor, inténtalo de nuevo.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Regresar a la página de inicio</a>
        </div>
    </div>
@endsection
