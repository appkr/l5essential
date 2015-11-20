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

if (! function_exists('icon')) {
    /**
     * Generate FontAwesome icon tag
     *
     * @param string $class font-awesome class name
     * @param string $addition additional class
     * @param string $inline inline style
     * @return string
     */
    function icon($class, $addition = 'icon', $inline = null) {
        $icon   = config('icons.' . $class);
        $inline = $inline ? 'style="' . $inline . '"' : null;

        return sprintf('<i class="%s %s" %s></i>', $icon, $addition, $inline);
    }
}

if (! function_exists('attachment_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function attachment_path($path = '')
    {
        return public_path($path ? 'attachments'.DIRECTORY_SEPARATOR.$path : 'attachments');
    }
}