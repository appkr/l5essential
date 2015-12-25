<?php

namespace App\Console;

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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\UpdateLessonsTable::class,
        \App\Console\Commands\BackupDb::class,
        \App\Console\Commands\ClearLog::class,
        \App\Console\Commands\PruneRelease::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->command('my:clear-log')->monthly();
        $schedule->command(sprintf(
                'my:backup-db %s %s',
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            ))->dailyAt('03:00');
    }
}
