<?php

Route::get('/', function() {
    throw new Exception('Some bad thing happened');
});

//Route::get('/', function() {
//    abort(404);
//});

//Route::get('/', function() {
//    return App\Post::findOrFail(100);
//});