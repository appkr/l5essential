<?php

Route::pattern('image', '(?P<parent>[0-9]{2}-[\pL-\pN\._-]+)-(?P<suffix>img-[0-9]{2}.png)');

Route::get('/', [
    'as' => 'index',
    'uses' => 'WelcomeController@index'
]);

Route::get('home', [
    'as' => 'home',
    'uses' => 'WelcomeController@home'
]);

/* Documents */
Route::get('docs/{image}', [
    'as'   => 'documents.image',
    'uses' => 'DocumentsController@image'
]);

Route::get('docs/{file?}', [
    'as'   => 'documents.show',
    'uses' => 'DocumentsController@show'
]);

/* User Registration */
Route::group(['prefix' => 'auth', 'as' => 'user.'], function () {
    Route::get('register', [
        'as'   => 'create',
        'uses' => 'Auth\AuthController@getRegister'
    ]);
    Route::post('register', [
        'as'   => 'store',
        'uses' => 'Auth\AuthController@postRegister'
    ]);
});

/* Session */
Route::group(['prefix' => 'auth', 'as' => 'session.'], function () {
    Route::get('login', [
        'as'   => 'create',
        'uses' => 'Auth\AuthController@getLogin'
    ]);
    Route::post('login', [
        'as'   => 'store',
        'uses' => 'Auth\AuthController@postLogin'
    ]);
    Route::get('logout', [
        'as'   => 'destroy',
        'uses' => 'Auth\AuthController@getLogout'
    ]);

    /* Social Login */
    Route::get('github', [
        'as'   => 'github.login',
        'uses' => 'Auth\AuthController@redirectToProvider'
    ]);
    Route::get('github/callback', [
        'as'   => 'github.callback',
        'uses' => 'Auth\AuthController@handleProviderCallback'
    ]);
});

/* Password Reminder */
Route::group(['prefix' => 'auth'], function () {
    Route::get('remind', [
        'as'   => 'reminder.create',
        'uses' => 'Auth\PasswordController@getEmail'
    ]);
    Route::post('remind', [
        'as'   => 'reminder.store',
        'uses' => 'Auth\PasswordController@postEmail'
    ]);
    Route::get('reset/{token}', [
        'as'   => 'reset.create',
        'uses' => 'Auth\PasswordController@getReset'
    ]);
    Route::post('reset', [
        'as'   => 'reset.store',
        'uses' => 'Auth\PasswordController@postReset'
    ]);
});
