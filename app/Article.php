<?php

namespace App;

class Article extends Model
{
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'notification',
        'solution_id'
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
        return $this->belongsToMany(Tag::class)->withTimestamps();
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

    /* Query Scope */

    public function scopeNoComment($query)
    {
        return $query->has('comments', '<', 1);
    }

    public function scopeNotSolved($query)
    {
        return $query->whereNull('solution_id');
    }

    /* Helpers */

    public function isAuthor()
    {
        return $this->author->id == auth()->user()->id;
    }
}
