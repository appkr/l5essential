<?php

namespace App\Listeners;

use App\Events\ModelChanged;

class CacheHandler
{
    /**
     * Handle the event.
     *
     * @param \App\Events\ModelChanged $event
     */
    public function handle(ModelChanged $event)
    {
        if (! taggable()) {
            // Remove all cache store
            return \Cache::flush();
        }

        // Remove only cache that has the given tag(s)
        return \Cache::tags($event->cacheTags)->flush();
    }
}
