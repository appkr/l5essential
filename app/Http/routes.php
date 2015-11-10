<?php

Route::get('auth', function () {
    $credentials = [
        'email'    => 'john@example.com',
        'password' => 'password'
    ];

    if (! Auth::attempt($credentials)) {
        return 'Incorrect username and password combination';
    }

    return redirect('protected');
});

Route::get('auth/logout', function () {
    Auth::logout();

    return 'See you again~';
});

Route::get('protected', function () {
    if (! Auth::check()) {
        return 'Illegal access !!! Get out of here~';
    }

    return 'Welcome back, ' . Auth::user()->name;
});

//Route::get('protected', [
//    'middleware' => 'auth',
//    function () {
//        return 'Welcome back, ' . Auth::user()->name;
//    }
//]);

Route::get('auth/login', function() {
    return "You've reached to the login page~";
});