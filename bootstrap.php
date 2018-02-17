<?php namespace TitusPiJean\Flarum\Auth\SSOwat;

use TitusPiJean\Flarum\Auth\SSOwat\Listener;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listeners\AddClientAssets::class);
    $events->subscribe(Listeners\AddSSOwatAuthRoute::class);
    $events->subscribe(Listeners\AddApiAttributes::class);
};
