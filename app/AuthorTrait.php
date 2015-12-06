<?php

namespace App;

trait AuthorTrait
{
    /**
     * Determine if the current instance was authored by the current user.
     *
     * @return bool
     */
    public function isAuthor()
    {
        return $this->author->id == auth()->user()->id;
    }
}