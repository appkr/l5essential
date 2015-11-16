<?php

namespace App;

use File;
use Image;

class Document
{

    /**
     * @var string Directory name that houses markdown files.
     */
    private $directory;

    /**
     * Constructor.
     *
     * @param string $directory
     */
    public function __construct($directory = 'docs')
    {
        $this->directory = $directory;
    }

    /**
     * Read the content of a given file.
     *
     * @param string|null $file
     * @return string
     */
    public function get($file = 'index.md')
    {
        return File::get($this->getPath($file));
    }

    /**
     * Calculate and respond image path.
     *
     * @param string $file
     * @return \Intervention\Image\Image
     */
    public function image($file)
    {
        return Image::make($this->getPath($file));
    }

    /**
     * Create etag value
     *
     * @param string $file
     * @return string
     */
    public function etag($file)
    {
        return md5($file . '/' . File::lastModified($this->getPath($file)));
    }

    /**
     * Calculate full path
     *
     * @param string $file
     * @return string
     */
    private function getPath($file)
    {
        $path = base_path($this->directory . DIRECTORY_SEPARATOR . $file);

        if (! File::exists($path)) {
            abort(404, 'File not exist');
        }

        return $path;
    }
}
