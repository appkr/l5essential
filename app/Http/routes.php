<?php

Route::get('/', function() {
    $text =<<<EOT
**Note** To make lists look nice, you can wrap items with hanging indents:

    -   Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
        Aliquam hendrerit mi posuere lectus. Vestibulum enim wisi,
        viverra nec, fringilla in, laoreet vitae, risus.
    -   Donec sit amet nisl. Aliquam semper ipsum sit amet velit.
        Suspendisse id sem consectetuer libero luctus adipiscing.
EOT;

    return app(ParsedownExtra::class)->text($text);
});