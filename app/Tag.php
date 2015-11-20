<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    /* Relationships */

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
