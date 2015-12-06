<?php

namespace App\Repositories;

use Cache;
use Exception;
use File;
use Illuminate\Database\Eloquent\Model;
use Image;

abstract class MarkdownRepository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var string Directory name, that houses the markdown files.
     */
    protected $path;

    /**
     * @var array Table of markdown files.
     */
    protected $toc;

    /**
     * @var string Currently selected markdown file.
     */
    protected $current;

    /**
     * Create DocumentRepository instance.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Specify an Eloquent Model's class name.
     *
     * @return string
     */
    public abstract function model();

    /**
     * Factory - new up the model and set the required properties.
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function initialize()
    {
        $model = app()->make($this->model());

        if (! $model instanceof Model) {
            throw new Exception(
                'model() method must return a string name of an Eloquent Model. Or the provided model cannot be instantiable.'
            );
        }

        if (! property_exists($this->model(), 'path')) {
            throw new Exception(
                "{$this->model()} should have a property named 'path'"
            );
        }

        $path  = base_path($model::$path);

        if (! File::isDirectory($path)) {
            throw new Exception(
                "Something went wrong with the path property of {$this->model()} model."
            );
        }

        $this->model = $model;
        $this->path  = $path;

        if (! $this->toc) {
            // Todo Expensive job. Should apply cache..
            $this->toc = Cache::remember('lessons.index', 120, function() use($model) {
                $all = glob(base_path($model::$path . DIRECTORY_SEPARATOR . '*.md'));
                $excepts = [];

                if (property_exists($this->model(), 'excepts')) {
                    foreach ($model::$excepts as $except) {
                        $excepts[] = base_path($model::$path . DIRECTORY_SEPARATOR . $except);
                    }
                }

                $files  = array_diff($all, $excepts);

                return array_map(function($file) {
                    return pathinfo($file, PATHINFO_BASENAME);
                }, $files);
            });
        }

        return;
    }

    /**
     * Get the collection of model.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Get the table of contents.
     *
     * @return array
     */
    public function toc()
    {
        return $this->toc;
    }

    /**
     * Get the currently selected markdown's filename.
     *
     * @return string
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Calculate previous entry.
     *
     * @param $current
     * @return bool
     */
    public function prev($current) {
        $prev = array_search($current, $this->toc) - 1;

        return array_key_exists($prev, $this->toc)
            ? $this->toc[$prev]
            : false;
    }

    /**
     * Calculate next entry.
     *
     * @param $current
     * @return mixed
     */
    public function next($current) {
        $next = array_search($current, $this->toc) + 1;

        return array_key_exists($next, $this->toc)
            ? $this->toc[$next]
            : false;
    }

    /**
     * Get the model instance.
     *
     * @param mixed $id filename
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*'])
    {
        $this->current = $id;

        return $this->model->whereName($id)->first()
            ?: $this->model->create([
                'author_id' => 1, // Bad!! Avoid hard code, b.c, admin may change.
                'name'      => $id,
                'content'   => File::get($this->getPath($id)),
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
     * Calculate full path to the given file.
     *
     * @param string $file
     * @return string
     */
    protected function getPath($file)
    {
        $path = $this->path . DIRECTORY_SEPARATOR . $file;

        if (!File::exists($path)) {
            abort(404, 'File not exist');
        }

        return $path;
    }
}
