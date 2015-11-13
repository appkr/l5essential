<?php

Route::get('docs/{file?}', [
    'as'   => 'documents.show',
    'uses' => 'DocumentsController@show'
]);

