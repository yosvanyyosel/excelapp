<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Excepciones que no se reportan
     */
    protected $dontReport = [
        //
    ];

    /**
     * Inputs que no se guardan en sesión
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Registrar callbacks de reporte y renderizado
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Lógica para reportar errores
        });

        $this->renderable(function (Throwable $e, $request) {
            // Lógica para renderizar errores personalizados
        });
    }
}
