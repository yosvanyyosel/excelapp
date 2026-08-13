<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Definir tareas programadas
     */
    protected function schedule(Schedule $schedule)
    {
        // Ejemplo: $schedule->command('emails:send')->daily();
    }

    /**
     * Registrar comandos Artisan
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
