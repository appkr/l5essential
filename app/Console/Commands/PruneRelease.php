<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class PruneRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:prune-release
        {path : Releases path.}
        {--K|keep=3 : Number of recent releases to keep.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old releases.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');

        $dirs = collect(File::directories($path))->sortByDesc(function($dir) {
            return File::lastModified($dir);
        });

        $dirs->values()->splice($this->option($keep))->map(function($dir) {
            File::deleteDirectory($dir);
            $this->info(sprintf('%s removed.', $dir));
        });

        $now = \Carbon\Carbon::now()->toDateTimeString();
        $result = sprintf(
            '%s command done at %s. %d %s removed',
            $this->getName(),
            $now,
            $dirs->count(),
            str_plural('release', $dirs->count())
        );
        \Log::info($result . ': ' . $dirs->toJson());

        return $this->warn($result);
    }
}
