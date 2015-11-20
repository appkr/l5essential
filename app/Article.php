<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'notification'
    ];

    protected $hidden = [
        'author_id',
        'solution_id',
        'notification'
    ];

    /* Relationships */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function solution()
    {
        return $this->hasOne(Comment::class, 'id', 'solution_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
