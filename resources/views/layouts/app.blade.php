<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- Vista mejorada de APP principal. --}}

<head>
    <!-- WEB DEVELOPER: HUGO JARAMILLO  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token Security -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logística y Administración</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />


    {{-- ESTILOS DE APP QUE SE CONSERVA EN EL LAYOUT PRINCIPAL --}}
    <style>
        body,
        html {
            height: 100%;
            width: 100%;
            margin: 0;
            line-height: 1.6;
            font-family: 'Nunito', sans-serif;
        }

        .carousel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            min-width: 100%;

        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Cambié a cover para mejor visualización */
            display: inline;
            /* Comienza desde el centro */
            transform-origin: initial;
            /* Origen de transformación en el centro */
            transition: opacity 3s ease-in;
            /* Transición suave de opacidad */
        }

        @keyframes slide {
            0% {
                opacity: 0;
                transform: scaleX(0);
                /* Comienza desde el centro */
            }

            10% {
                opacity: 1;
                /* Muestra la imagen */
                transform: scaleX(1);
                /* Expande hasta ocupar todo el ancho */
            }

            30% {
                opacity: 1;
                /* Mantiene la imagen visible */
                transform: scaleX(1);
            }

            40% {
                opacity: 0;
                /* Comienza a ocultar la imagen */
                transform: scaleX(0);
                /* Se desliza hacia afuera */
            }

            100% {
                opacity: 0;
                /* Finaliza oculta */
            }
        }

        .form-container {
            position: relative;
            z-index: 2;
            /* Asegúrate de que el formulario esté encima del carrusel */
            border-radius: 16px;
            /* Bordes un poco más redondeados */
            max-width: 950px;
            width: 100%;
            /* Ajustar el ancho para más espacio */
            margin: 6rem auto;
            /* Centrar el formulario */
            padding: 2rem;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            /* Asegurar que ocupen todo el ancho */
            padding: 0.8rem;
            /* Espaciado interno */
            margin-bottom: 1rem;
            /* Espaciado entre campos */
            border: 1px solid #bdb9b9;
            /* Borde claro */
            border-radius: 6px;
            /* Bordes redondeados */
            transition: border 0.3s;
            /* Transición suave para el borde */
        }

        .form-container button {
            width: 50%;
            /* Asegurar que el botón ocupe todo el ancho */
            padding: 0.8rem;
            /* Espaciado interno */
            background-color: #4da3ff;
            /* Color de fondo del botón */
            color: white;
            /* Color del texto */
            border: none;
            /* Sin borde */
            border-radius: 6px;
            /* Bordes redondeados */
            cursor: pointer;
            /* Cambiar el cursor al pasar el mouse */
            transition: background 0.3s;
            /* Transición suave para el fondo */
        }

        .form-container button:hover {
            background-color: #00b345;
            /* Color del botón al pasar el mouse */
        }

        strong {
            color: #579e72
        }

        .navbar {
            position: fixed;
            /* Mantener la navbar fija en la parte superior */
            top: 0;
            /* Asegurar que esté en la parte superior */
            left: 0;
            width: 100%;
            /* Asegurar que ocupe todo el ancho */
            z-index: 4;
            /* Asegurar que esté encima de otros elementos */
            padding: 10px 20px;
            /* Espaciado interno */
        }
    </style>
</head>

<body>
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <strong>LOAD</strong>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- You can add more links here -->
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="carousel slide carousel-fade" id="imageCarousel" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('img/almacen.jpg') }}" class="d-block w-100" alt="Imagen 1">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('img/raks.jpg') }}" class="d-block w-100" alt="Imagen 2">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('img/load.jpg') }}" class="d-block w-100" alt="Imagen 2">
                </div>
            </div>
        </div>
        <main class="py-4">
            <div class="container form-container">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>

</html>
