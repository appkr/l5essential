<?php

if (! function_exists('markdown')) {
    /**
     * Compile the given text to markdown document.
     *
     * @param string $text
     * @return string
     */
    function markdown($text) {
        return app(ParsedownExtra::class)->text($text);
    }
}