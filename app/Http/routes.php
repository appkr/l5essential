<?php

$domain = env('API_DOMAIN', 'api.myproject.dev');

Route::group(['domain' => $domain, 'as' => 'api.', 'namespace' => 'Api'], function() {
    /* Landing page */
    Route::get('/', [
        'as'   => 'index',
        'uses' => 'WelcomeController@index'
    ]);

    /* User Registration */
    Route::post('auth/register', [
        'as'   => 'users.store',
        'uses' => 'UsersController@store'
    ]);

    /* Session.
     * In API, logout path is not required. Because,
     * when token is expired, any API request will not be validated.
     */
    Route::post('auth/login', [
        'as'   => 'sessions.store',
        'uses' => 'SessionsController@store'
    ]);

    /* Social Login
     * In API, social login is not provided.
     * Each client has to integrate an Oauth library, and
     * call 'POST auth/register' route in a onOauthLoginSuccessCallback.
     */

    /* Password Reminder.
     * Password reset is possible only through the web page.
     * For api client, this endpoint will accept user's email address
     * and send the user email which contains password reset token.
     */
    Route::post('auth/remind', [
        'as'   => 'remind.store',
        'uses' => 'PasswordsController@postRemind',
    ]);

    /* api.v1 */
    Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function() {
        /* Landing page */
        Route::get('/', [
            'as'   => 'v1.index',
            'uses' => 'WelcomeController@index'
        ]);

        /* Api documents */
        Route::get('docs', [
            'as'   => 'v1.docs',
            'uses' => 'DocumentsController@show'
        ]);

        Route::resource('articles', 'ArticlesController', ['only' => ['index']]);
    });
});

/* Landing page */
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

/* Mailing list */
//Route::post('mail-list/subscribe', [
//    'as'   => 'mail-list.subscribe',
//    'uses' => 'MailListController@subscribe',
//]);
//
//Route::delete('mail-list/unsubscribe', [
//    'as'   => 'mail-list.unsubscribe',
//    'uses' => 'MailListController@unsubscribe',
//]);

/* Documents */
Route::get('lessons/{image}', [
    'as'   => 'lessons.image',
    'uses' => 'LessonsController@image',
]);

Route::get('lessons/{file?}', [
    'as'   => 'lessons.show',
    'uses' => 'LessonsController@show',
]);

/* Forum */
Route::get('tags/{slug}/articles', [
    'as'   => 'tags.articles.index',
    'uses' => 'ArticlesController@index'
]);
Route::put('articles/{articles}/pick', [
    'as'   => 'articles.pick-best-comment',
    'uses' => 'ArticlesController@pickBest'
]);
Route::resource('articles', 'ArticlesController');

/* Attachments */
Route::resource('files', 'AttachmentsController', ['only' => ['store', 'destroy']]);

/* Comments */
Route::post('comments/{id}/vote', 'CommentsController@vote');
Route::resource('comments', 'CommentsController', ['only' => ['store', 'update', 'destroy']]);

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

/* From Laravel 5.2 all built-in events are fired in the form of Object */
//DB::listen(function($event){
//    var_dump($event->sql);
//    var_dump($event->bindings);
//    //var_dump($event->time);
//});