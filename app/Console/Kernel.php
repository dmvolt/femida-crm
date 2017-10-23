<?php

namespace App\Console;

use App\Console\Commands\CreateTaskNotification;
use App\Console\Commands\FixContactNumbers;
use App\Console\Commands\ImportContactCSV;
use App\Console\Commands\SendMessageNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateTaskNotification::class,
        ImportContactCSV::class,
        SendMessageNotification::class,
        FixContactNumbers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:CreateTaskNotification')->everyMinute();
        $schedule->command('command:SendMessageNotification')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
