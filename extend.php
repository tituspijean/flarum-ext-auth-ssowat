<?php namespace TitusPiJean\Flarum\Auth\SSOwat;

use Flarum\Extend;
use Illuminate\Events\Dispatcher;

return [
  (new Extend\Frontend('forum'))
    ->js(__DIR__.'/js/dist/forum.js')
    ->css(__DIR__.'/less/forum.less'),
  (new Extend\Frontend('admin'))
    ->js(__DIR__.'/js/dist/admin.js'),
  (new Extend\Locales(__DIR__ . '/locale')),
  (new Extend\Routes('forum'))
    ->get('/ssowat/login', 'auth.ssowat', Controllers\SSOwatLogInController::class),
  (new Extend\Routes('forum'))
    ->get('/ssowat/logout', 'logout.ssowat', Controllers\SSOwatLogOutController::class),
  (new Extend\Middleware('frontend'))->add(Middleware\SSOwatMiddleware::class)
];
