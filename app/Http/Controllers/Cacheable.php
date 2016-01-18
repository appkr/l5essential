<?php

namespace App\Http\Controllers;

interface Cacheable
{
    /**
     * Specify the tags for caching.
     * @see https://laravel.com/docs/cache#cache-tags
     *
     * @return string
     */
    public function cacheKeys();
}