<?php

Route::get('auth', function () {
    $credentials = [
        'email'    => 'john@example.com',
        'password' => 'password'
    ];

    if (! Auth::attempt($credentials)) {
        return 'Incorrect username and password combination';
    }

    Event::fire('user.login', [Auth::user()]);

    var_dump('Event fired and continue to next line...');

    return;
});

//Event::listen('user.login', function($user) {
//    var_dump('"user.log" event catched and passed data is:');
//    var_dump($user->toArray());
//});

Event::listen('user.login', function($user) {
    $user->last_login = (new DateTime)->format('Y-m-d H:i:s');

    return $user->save();
});