<?php

if (!function_exists('markdown')) {
    /**
     * Compile the given text to markdown document.
     *
     * @param string $text
     * @return string
     */
    function markdown($text)
    {
        return app(ParsedownExtra::class)->text($text);
    }
}

if (!function_exists('icon')) {
    /**
     * Generate FontAwesome icon tag
     *
     * @param string $class    font-awesome class name
     * @param string $addition additional class
     * @param string $inline   inline style
     * @return string
     */
    function icon($class, $addition = 'icon', $inline = null)
    {
        $icon = config('icons.' . $class);
        $inline = $inline ? 'style="' . $inline . '"' : null;

        return sprintf('<i class="%s %s" %s></i>', $icon, $addition, $inline);
    }
}

if (!function_exists('attachment_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function attachment_path($path = '')
    {
        return public_path($path ? 'attachments' . DIRECTORY_SEPARATOR . $path : 'attachments');
    }
}

if (!function_exists('gravatar_profile_url')) {
    /**
     * Get gravatar profile page url
     *
     * @param  string $email
     * @return string
     */
    function gravatar_profile_url($email)
    {
        return sprintf("//www.gravatar.com/%s", md5($email));
    }
}

if (!function_exists('gravatar_url')) {
    /**
     * Get gravatar image url
     *
     * @param  string  $email
     * @param  integer $size
     * @return string
     */
    function gravatar_url($email, $size = 72)
    {
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
    }
}