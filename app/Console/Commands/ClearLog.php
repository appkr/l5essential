<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:clear-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Laravel log.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = storage_path('logs/laravel.log');
        system('cat /dev/null > ' . $path);

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $result = "{$this->getName()} command done at {$now}";
        \Log::info($result);

        return $this->info($result);
    }
}
