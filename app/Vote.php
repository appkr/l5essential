<?php

namespace App;

class Vote extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'comment_id',
        'up',
        'down',
        'voted_at',
    ];

    protected $dates = [
        'voted_at',
    ];

    /* Relationships */

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
