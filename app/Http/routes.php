<?php

Route::pattern('image', '(?P<parent>[0-9]{2}-[\pL-\pN\._-]+)-(?P<suffix>img-[0-9]{2}.png)');

Route::get('docs/{image}', [
    'as'   => 'documents.image',
    'uses' => 'DocumentsController@image'
]);

Route::get('docs/{file?}', [
    'as'   => 'documents.show',
    'uses' => 'DocumentsController@show'
]);

