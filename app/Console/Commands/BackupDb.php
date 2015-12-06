<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:backup-db
        {user : User name for database login.}
        {pass : Password for database login.}
        {--S|db=myProject : Database name to backup.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute database backup.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dir = storage_path('backup');

        if (! \File::isDirectory($dir)) {
            \File::makeDirectory($dir);
        }

        $command = sprintf(
            'mysqldump %s > %s -u%s -p%s',
            $this->option('db'),
            storage_path("backup/{$this->option('db')}.sql"),
            $this->argument('user'),
            $this->argument('pass')
        );

        system($command);

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $result = "{$this->getName()} command done at {$now}";
        \Log::info($result);

        return $this->info($result);
    }
}
