<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
//    public $timestamps = false;

    protected $fillable = ['title', 'body'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
