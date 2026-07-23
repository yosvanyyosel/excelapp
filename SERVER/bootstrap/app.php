<?php

use App\Http\Middleware\VerificaAccesoIglesia;
use App\Http\Middleware\VerificaAccesoMinisterio;
use App\Http\Middleware\VerificaAccesosGenerales;
use App\Http\Middleware\VerificaPermisoMinisterio;
use App\Http\Middleware\VerificaPermisoModuloIglesia;
use App\Http\Middleware\VerificaRolAdministrador;
use App\Http\Middleware\VerificaRolEnMinisterio;
use App\Http\Middleware\VerificaRolMiembro;
use App\Http\Middleware\VerificaRolPastor;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
          // Registrar middlewares globales (se ejecutan en cada request)
        /*  $middleware->web(append: [
            \App\Http\Middleware\CurrentIglesia::class,
        ]);*/
        
        
        $middleware->alias([
            'rol_permisos' => \App\Http\Middleware\RolPermisosMiddleware::class,
            'suscripcion' => \App\Http\Middleware\VerificarSuscripcion::class,
            'rol.admin' => \App\Http\Middleware\RolAdmin::class,
            'rol.web' => \App\Http\Middleware\RolWeb::class,
           // 'permiso.modulo' => \App\Http\Middleware\CheckModulePermission::class,
            //'role' => \App\Http\Middleware\EnsureUserRole::class
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
