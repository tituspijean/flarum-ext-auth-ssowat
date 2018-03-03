<?php namespace TitusPiJean\Flarum\Auth\SSOwat;

use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events, Application $app) {
    $events->subscribe(Listeners\AddMiddleware::class);
    $events->subscribe(Listeners\AddClientAssets::class);
    $events->subscribe(Listeners\AddSSOwatRoutes::class);
    $events->subscribe(Listeners\AddApiAttributes::class);
};
