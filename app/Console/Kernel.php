<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Nettoyer les paniers expirés toutes les minutes et libérer le stock réservé
        $schedule->command('carts:cleanup')
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();

        // Vérification des stocks critiques quotidienne
        $schedule->command('stock:check-critical')
            ->dailyAt(config('stock.notification_time'))
            ->timezone('Europe/Paris');
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
