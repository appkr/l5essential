<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
