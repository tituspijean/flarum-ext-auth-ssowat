<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Listeners;

use Flarum\Event\ConfigureForumRoutes;
use Illuminate\Contracts\Events\Dispatcher;

class AddSSOwatAuthRoute
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureForumRoutes::class, [$this, 'configureForumRoutes']);
    }

    /**
     * @param ConfigureForumRoutes $event
     */
    public function configureForumRoutes(ConfigureForumRoutes $event)
    {
        $event->get('/auth/ssowat', 'auth.ssowat', 'TitusPiJean\Flarum\Auth\SSOwat\Controllers\SSOwatLogInController');
    }
}
