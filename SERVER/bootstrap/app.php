<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernelBase;
use Illuminate\Foundation\Console\Kernel as ConsoleKernelBase;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandlerBase;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Console\Scheduling\Schedule;
use Throwable;

/**
 * Http Kernel
 */
class HttpKernel extends HttpKernelBase
{
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ],
        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
    ];
}

/**
 * Console Kernel
 */
class ConsoleKernel extends ConsoleKernelBase
{
    protected function schedule(Schedule $schedule)
    {
        // Ejemplo: $schedule->command('emails:send')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/../app/Console/Commands');
        require __DIR__.'/../routes/console.php';
    }
}

/**
 * Exception Handler
 */
class Handler extends ExceptionHandlerBase
{
    protected $dontReport = [];

    protected $dontFlash = ['password','password_confirmation'];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // lógica de reporte
        });

        $this->renderable(function (Throwable $e, $request) {
            // lógica de renderizado
        });
    }
}

/**
 * Bootstrap Application
 */
$app = new Application(dirname(__DIR__));

$app->singleton(HttpKernelContract::class, HttpKernel::class);
$app->singleton(ConsoleKernelContract::class, ConsoleKernel::class);
$app->singleton(ExceptionHandlerContract::class, Handler::class);

return $app;
