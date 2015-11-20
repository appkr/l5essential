<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'author_id',
        'parent_id',
        'title',
        'content'
    ];

    protected $hidden = [
        'author_id',
        'commentable_type',
        'commentable_id',
        'parent_id'
    ];

    /* Relationships */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'id', 'parent_id');
    }
}
