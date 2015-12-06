<?php

namespace App\Console\Commands;

use App\DocumentRepository;
use Illuminate\Console\Command;

class UpdateLessonsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:update-lessons {--A|all=true : If true, all lesson files be processed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the content of the lessons table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lessons = \App\Lesson::all();

        foreach($lessons as $lesson) {
            $path = base_path(\App\Lesson::$path . DIRECTORY_SEPARATOR . $lesson->name);
            $lesson->content = \File::get($path);
            $lesson->save();
            $lesson->touch();

            $this->info(sprintf('Success updating %d: %s', $lesson->id, $lesson->name));
        }

        return $this->warn('Finished.');
    }
}
