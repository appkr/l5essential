<?php

namespace App;

use File;

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
        $path = $this->getPath($file);

        if (! File::exists($path)) {
            abort(404, 'File not exist');
        }

        return File::get($path);
    }

    /**
     * Calculate and respond image path
     *
     * @param string $file
     * @return mixed
     */
    public function imagePath($file)
    {
        $path = $this->getPath($file);

        if (! File::exists($path)) {
            abort(404, 'Image not exist');
        }

        return $path;
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
