<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Middleware;

use Flarum\Foundation\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Illuminate\Events\Dispatcher;
use Flarum\Http\SessionAuthenticator;
use Flarum\Http\Rememberer;
use Flarum\User\Event\LoggedOut;
use Flarum\User\Exception\PermissionDeniedException;
use Flarum\Settings\SettingsRepositoryInterface;

class SSOwatMiddleware implements MiddlewareInterface
{

  /**
* @param Application $app
* @param Dispatcher $events
* @param SessionAuthenticator $authenticator
* @param Rememberer $rememberer
*/
public function __construct(
    Application $app,
    Dispatcher $events,
    SessionAuthenticator $authenticator,
    Rememberer $rememberer,
    SettingsRepositoryInterface $settings
) {
    $this->app = $app;
    $this->events = $events;
    $this->settings = $settings;
    $this->authenticator = $authenticator;
    $this->rememberer = $rememberer;
}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Retrieve the session and check if the user logged in with SSOwat
        $session=$request->getAttribute('session');
        $ssowatUser=$session->get('ssowatUser');
        if ($ssowatUser) {
            if ($ssowatUser > 1) {
            $session->put('ssowatUser', $ssowatUser - 1);
            } else {
                // If so, retrieve their username and
                // check if it is the same as the one provided by SSOwat
                $actor=$request->getAttribute('actor');
                if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $actor->username) {
                    // If not, do the same steps as Flarum's LogOutController
                    // Prepare redirection to SSOwat
                    $ssowat = $this->settings->get('tituspijean-auth-ssowat.domain');
                    $url = array_get($request->getQueryParams(), 'return', $this->app->url());
                    $r = base64_encode($url);
                    //$r = base64_encode("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                    $urlredirect = "https://" . $ssowat . "/yunohost/sso/?action=logout&r=" . $r;
                    $response    = new RedirectResponse($urlredirect);
                    //Log out
                    $this->authenticator->logOut($session);
                    $actor->accessTokens()->delete();
                    $this->events->fire(new LoggedOut($actor));
                    $this->rememberer->forget($response);
                    // Throw an error if JSON was requested, or redirect to logout
                    if (str_contains($request->getHeaderLine('content-type'), 'json')) {
                        throw new PermissionDeniedException("You have been logged out from YunoHost.", 401, null);
                    } else {
                        return new RedirectResponse($this->app->url()."/logout?token=".$session->get('csrf_token'));
                    }
                }
            }
        }
        // Else, proceed
        return $handler->handle($request);
    }
}
