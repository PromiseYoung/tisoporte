@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="alert alert-warning text-center" role="alert">
            <h1 class="display-4">¡Oops! Página No Encontrada.</h1>
            <p class="lead">Lo sentimos, la página que buscas no existe.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Regresar a la página de inicio</a>
        </div>
    </div>
@endsection
