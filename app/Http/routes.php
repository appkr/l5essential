<?php

Route::get('posts', [
    'as'   => 'posts.index',
    'uses' => 'PostsController@index'
]);

//Route::get('posts', [
//    'as' => 'posts.index',
//    function() {
//        return view('posts.index');
//    }
//]);