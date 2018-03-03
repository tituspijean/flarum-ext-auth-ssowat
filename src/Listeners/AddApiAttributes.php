<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Listeners;

use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Flarum\Event\PrepareApiAttributes;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Foundation\Application;
use Flarum\Http\UrlGenerator;
use Zend\Diactoros\Response\RedirectResponse;

class AddApiAttributes
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(Application $app, SettingsRepositoryInterface $settings)
    {
        $this->app = $app;
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(PrepareApiAttributes::class, [$this, 'prepareApiAttributes']);
    }

    public function prepareApiAttributes(PrepareApiAttributes $event)
    {
        if ($event->isSerializer(ForumSerializer::class)) {
            $event->attributes['ssowat.domain'] = $this->settings->get('tituspijean-auth-ssowat.domain');
            $event->attributes['ssowat.onlyUse'] = (bool) $this->settings->get('tituspijean-auth-ssowat.onlyUse');
            if ($event->actor->getSession()->get('ssowatUser')) {
                $event->attributes['ssowat.user'] = true;
            }
        }
    }
}
