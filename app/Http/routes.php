<?php

Route::pattern('image', '(?P<parent>[0-9]{2}-[\pL-\pN\._-]+)-(?P<suffix>img-[0-9]{2}.png)');

Route::get('/', [
    'as'   => 'index',
    'uses' => 'WelcomeController@index',
]);

Route::get('home', [
    'as'   => 'home',
    'uses' => 'WelcomeController@home',
]);

Route::get('locale', [
    'as'   => 'locale',
    'uses' => 'WelcomeController@locale',
]);

/* Documents */
Route::get('docs/{image}', [
    'as'   => 'documents.image',
    'uses' => 'DocumentsController@image',
]);

Route::get('docs/{file?}', [
    'as'   => 'documents.show',
    'uses' => 'DocumentsController@show',
]);

/* Forum */
Route::get('tags/{id}/articles', [
    'as'   => 'tags.articles.index',
    'uses' => 'ArticlesController@index'
]);
Route::resource('articles', 'ArticlesController');

/* Attachments */
Route::resource('files', 'AttachmentsController', ['only' => ['store', 'destroy']]);

/* User Registration */
Route::get('auth/register', [
    'as'   => 'users.create',
    'uses' => 'UsersController@create'
]);
Route::post('auth/register', [
    'as'   => 'users.store',
    'uses' => 'UsersController@store'
]);

/* Social Login */
Route::get('social/{provider}', [
    'as'   => 'social.login',
    'uses' => 'SocialController@execute',
]);

/* Session */
Route::get('auth/login', [
    'as'   => 'sessions.create',
    'uses' => 'SessionsController@create'
]);
Route::post('auth/login', [
    'as'   => 'sessions.store',
    'uses' => 'SessionsController@store'
]);
Route::get('auth/logout', [
    'as'   => 'sessions.destroy',
    'uses' => 'SessionsController@destroy'
]);

/* Password Reminder */
Route::get('auth/remind', [
    'as'   => 'remind.create',
    'uses' => 'PasswordsController@getRemind',
]);
Route::post('auth/remind', [
    'as'   => 'remind.store',
    'uses' => 'PasswordsController@postRemind',
]);
Route::get('auth/reset/{token}', [
    'as'   => 'reset.create',
    'uses' => 'PasswordsController@getReset',
]);
Route::post('auth/reset', [
    'as'   => 'reset.store',
    'uses' => 'PasswordsController@postReset',
]);
