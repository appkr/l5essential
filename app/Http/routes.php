<?php

Route::get('/', function () {
    $items = [
        'Apple',
        'Banana'
    ];

    return view('index', compact('items'));
});
