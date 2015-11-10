<?php

Route::get('/', function() {
    return 'See you soon~';
});

Route::get('home', [
    'middleware' => 'auth',
    function() {
        return 'Welcome back, ' . Auth::user()->name;
    }
]);

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');