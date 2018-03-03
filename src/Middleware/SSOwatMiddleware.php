<?php

namespace TitusPiJean\Flarum\Auth\SSOwat\Middleware;

use Flarum\Foundation\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Contracts\Events\Dispatcher;
use Flarum\Http\SessionAuthenticator;
use Flarum\Http\Rememberer;
use Flarum\Event\UserLoggedOut;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Stratigility\MiddlewareInterface;
use Flarum\Core\Exception\PermissionDeniedException;

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
        Rememberer $rememberer
    ) {
        $this->app = $app;
        $this->events = $events;
        $this->authenticator = $authenticator;
        $this->rememberer = $rememberer;
    }
    /**
    *
    *
    * @param Request $request
    * @param Response $response
    * @param callable $out
    * @return Response
    */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        // Retrieve the session and check if the user logged in with SSOwat
        $session=$request->getAttribute('session');
        if ($session->get('ssowatUser')) {
            // If so, retrieve their username and
            // check if it is the same as the one provided by SSOwat
            $actor=$request->getAttribute('actor');
            if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $actor->username) {
                // If not, do the same steps as Flarum's LogOutController
                $this->authenticator->logOut($session);
                $actor->accessTokens()->delete();
                $this->events->fire(new UserLoggedOut($actor));
                $this->rememberer->forget($response);
                // Throw an error if JSON was requested, or redirect to logout
                if (str_contains($request->getHeaderLine('content-type'), 'json')) {
                    throw new PermissionDeniedException("You have been logged out from YunoHost.", 401, null);
                } else {
                    return new RedirectResponse($this->app->url()."/logout?token=".$session->get('csrf_token'));
                }
            }
        }
        // Else, proceed
        return $out ? $out($request, $response) : $response;
    }
}
