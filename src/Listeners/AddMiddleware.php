<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Listeners;

use Flarum\Event\ConfigureMiddleware;
use Illuminate\Events\Dispatcher;
use TitusPiJean\Flarum\Auth\SSOwat\Middleware\SSOwatMiddleware;

class AddMiddleware
{
    /**
     * Subscribes to the Flarum events.
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureMiddleware::class, [$this, 'addMiddleware']);
    }
    /**
     * @param ConfigureMiddleware $event
     */
    public function addMiddleware(ConfigureMiddleware $event)
    {
        $event->pipe(app(SSOwatMiddleware::class));
    }
}
