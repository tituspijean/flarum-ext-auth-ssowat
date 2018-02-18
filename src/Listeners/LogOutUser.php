<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Listeners;

use Flarum\Core\User;
use Flarum\Event\UserLoggedOut;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;

class LogOutUser {
    protected $settings;
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserLoggedOut::class, [$this, 'addLogoutRedirect']);
    }
    public function addLogoutRedirect(UserLoggedOut $event)
    {
      if isset($_SERVER['PHP_AUTH_USER']) {
      $ssowat = $this->settings->get('flarum-ext-auth-ssowat.address');
      $ssowat_user = $_SERVER['PHP_AUTH_USER'];
      $flarum_user = $event->user->username;
        if ( $ssowat_user == $flarum_user ) {
          $r           = base64_encode( "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
          $urlredirect = "https://" . $ssowat . "/yunohost/sso/?action=logout&r=" . $r;
          header("Location: " . $urlredirect . $r); /* Redirect browser */
          exit; // Stop at SSO login
        } else {
          // Nothing
        }
      } else {
      // Nothing
      }
    }
}
