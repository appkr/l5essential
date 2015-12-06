<?php

namespace App\Repositories;

class LessonRepository extends MarkdownRepository
{
    /**
     * Get the index of documents.
     *
     * @return mixed
     */
    public function index()
    {
        return $this->find('INDEX.md');
    }

    /**
     * Specify an Eloquent Model's class name.
     *
     * @return string
     */
    public function model()
    {
        return \App\Lesson::class;
    }
}