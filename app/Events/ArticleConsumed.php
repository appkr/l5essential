<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ArticleConsumed extends Event
{
    use SerializesModels;

    /**
     * @var \App\Article
     */
    public $article;

    /**
     * Create a new event instance.
     *
     * @param \App\Article $article
     */
    public function __construct(\App\Article $article)
    {
        $this->article = $article;
    }
}
