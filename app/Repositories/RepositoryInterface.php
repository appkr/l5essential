<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get the model instance.
     *
     * @param mixed $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*']);

    /**
     * Get the collection of model.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*']);
}