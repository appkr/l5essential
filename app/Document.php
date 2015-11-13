<?php

namespace App;

use Illuminate\Support\Facades\File;

class Document
{

    /**
     * @var string Directory name that houses markdown files.
     */
    private $directory = 'docs';

    /**
     * Read the content of a given file.
     *
     * @param string $file
     * @return mixed
     */
    public function get($file = 'index.md')
    {
        if (! File::exists($this->getPath($file))) {
            abort(404, 'File not exist');
        }

        return File::get($this->getPath($file));
    }

    /**
     * Calculate full path
     *
     * @param null $file
     * @return string
     */
    private function getPath($file = null)
    {
        return base_path(
            $file
                ? $this->directory . DIRECTORY_SEPARATOR . $file
                : $this->directory
        );
    }

}
