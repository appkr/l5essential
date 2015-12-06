<?php

namespace App;

class Lesson extends Model
{
    use AuthorTrait;

    // Directory name that holds markdown files
    // that is corresponding to this Eloquent model.
    // This should be relative to project root. e.g. docs/version1
    public static $path = 'lessons';

    // List of files that should not be included
    // when generating index of files.
    public static $excepts = [
        'INDEX.md',
        'SUMMARY.md',
        'GLOSSARY.md',
        // 'README.md',
    ];

    protected $fillable = [
        'author_id',
        'name',
        'content',
    ];

    /* Relationships */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
