<?php

namespace App;

class Document extends Model
{
    use AuthorTrait;

    protected $fillable = [
        'author_id',
        'name',
        'content'
    ];

    /* Relationships */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
