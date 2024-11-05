<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        // Aquí puedes agregar excepciones que no deseas reportar
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Puedes registrar excepciones personalizadas aquí
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Manejo de excepciones HTTP
        if ($exception instanceof HttpException) {
            switch ($exception->getStatusCode()) {
                case 500:
                    return response()->view('errors.500', [], 500);
                case 419:
                    return response()->view('errors.419', [], 419);
                case 403:
                    return response()->view('errors.403', [], 403);
                case 404:
                    return response()->view('errors.404', [], 404);
                    // Puedes agregar más casos según sea necesario
            }
        }
        // Llama al método padre para manejar otras excepciones
        return parent::render($request, $exception);
    }
}
