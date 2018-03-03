<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Listeners;

use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use Flarum\Event\ConfigureMiddleware;
use TitusPiJean\Flarum\Auth\SSOwat\Middleware\SSOwatMiddleware;

class AddMiddleware
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureMiddleware::class, [$this, 'prevent']);
    }

    public function prevent(ConfigureMiddleware $event)
    {
        $event->pipe(app(SSOwatMiddleware::class));
    }
}
