<?php

namespace App;

class Attachment extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'article_id'
    ];

    /* Relationships */

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
