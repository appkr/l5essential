<?php

// Data binding using with() method
Route::get('/', function () {
    $greeting = 'Hello';

    return view('index')->with('greeting', $greeting);
});

//// Binding more than two variables
//Route::get('/', function () {
//    return view('index')->with([
//        'greeting' => 'Good morning ^^/',
//        'name'     => 'Appkr'
//    ]);
//});
//
//// Binding data as a second argument of view() helper
//Route::get('/', function () {
//    return view('index', [
//        'greeting' => 'Ola~',
//        'name'     => 'Laravelians'
//    ]);
//});
//
//// Binding data as a property of View instance
//Route::get('/', function () {
//    $view = view('index');
//    $view->greeting = "Hey~ What's up";
//    $view->name = 'everyone';
//
//    return $view;
//});
