<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ModelChanged::class => [
            \App\Listeners\CacheHandler::class
        ],
        \App\Events\ArticleConsumed::class => [
            \App\Listeners\ViewCountHandler::class
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $events->listen('comments.*', \App\Listeners\CommentsHandler::class);
        $events->subscribe(\App\Listeners\UserEventsHandler::class);
    }
}
