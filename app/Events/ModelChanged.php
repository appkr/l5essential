<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ModelChanged extends Event
{
    use SerializesModels;

    /**
     * @var string|array
     */
    public $cacheTags;

    /**
     * Create a new event instance.
     *
     * @param string $cacheTags
     */
    public function __construct($cacheTags)
    {
        $this->cacheTags = $cacheTags;
    }
}
