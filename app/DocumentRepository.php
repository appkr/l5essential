<?php

namespace App;

use File;
use Image;

class DocumentRepository
{
    /**
     * Get the index of documents.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->find('index.md');
    }

    /**
     * Get the model instance or create one.
     *
     * @param string $file
     * @return mixed
     */
    public function find($file)
    {
        return Document::whereName($file)->first()
            ?: Document::create([
                'author_id' => 1, // Bad!! Avoid hard code, b.c, admin may change.
                'name'      => $file,
                'content'   => File::get($this->getPath($file)),
            ]);
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
    protected function getPath($file)
    {
        $path = base_path('docs' . DIRECTORY_SEPARATOR . $file);

        if (!File::exists($path)) {
            abort(404, 'File not exist');
        }

        return $path;
    }
}
