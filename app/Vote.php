<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Vote extends Eloquent
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'up',
        'down',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
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
