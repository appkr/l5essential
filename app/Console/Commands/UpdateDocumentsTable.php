<?php

namespace App\Console\Commands;

use App\DocumentRepository;
use Illuminate\Console\Command;

class UpdateDocumentsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'myproject:update-documents-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the content of the documents table.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $documents = \App\Document::all();

        foreach($documents as $document) {
            $path = base_path('docs' . DIRECTORY_SEPARATOR . $document->name);
            $document->content = \File::get($path);
            $document->save();
            $document->touch();

            $this->info(sprintf('Success updating %d: %s', $document->id, $document->name));
        }

        $this->warn('Finished.');
    }
}
