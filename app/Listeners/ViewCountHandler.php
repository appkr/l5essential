<?php

namespace App\Listeners;

use App\Events\ArticleConsumed;

class ViewCountHandler
{
    /**
     * Handle the event.
     *
     * @param  ArticleConsumed  $event
     * @return void
     */
    public function handle(ArticleConsumed $event)
    {
        // Increase view count
        $event->article->view_count ++;
        $event->article->save();
    }
}
