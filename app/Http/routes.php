<?php

Route::get('posts', function() {
    $posts = App\Post::get();

    return view('posts.index', compact('posts'));
});

//Route::get('posts', function() {
//    $posts = App\Post::with('user')->get();
//
//    return view('posts.index', compact('posts'));
//});
//
//Route::get('posts', function() {
//    $posts = App\Post::get();
//    $posts->load('user');
//
//    return view('posts.index', compact('posts'));
//});

DB::listen(function($sql, $bindings, $time){
    var_dump($sql);
});