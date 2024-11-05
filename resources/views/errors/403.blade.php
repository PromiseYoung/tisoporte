@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="alert alert-danger text-center" role="alert">
            <h1 class="display-4">¡Acceso Prohibido!</h1>
            <p class="lead">No tienes permiso para acceder a esta página.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Regresar a la página de inicio</a>
        </div>
    </div>
@endsection
