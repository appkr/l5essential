<?php

Route::get('docs/index', function() {
    return (new App\Document)->index();
});

Route::get('docs/{file?}', function($file = null) {
    $text = (new App\Document)->get($file);

    return app(ParsedownExtra::class)->text($text);
});

