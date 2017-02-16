
<?php

use TitusPiJean\Flarum\Auth\SSOwat\Listener;
use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listener\AddClientAssets::class);
    $events->subscribe(Listener\AddSSOwatAuthRoute::class);
    $events->subscribe(Listener\AddApiAttributes::class);
};
