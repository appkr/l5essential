<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use AuthorTrait;

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
        'parent_id',
        'deleted_at',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $with = [
        'author',
        'votes',
    ];

    protected $appends = [
        'up_count',
        'down_count'
    ];

    /* Accessors */

    public function getUpCountAttribute()
    {
        return (int) static::votes()->sum('up');
    }

    public function getDownCountAttribute()
    {
        return (int) static::votes()->sum('down');
    }

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

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
