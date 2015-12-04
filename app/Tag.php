<?php

namespace App;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    /* Relationships */

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }
}
