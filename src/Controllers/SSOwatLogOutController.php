<?php namespace TitusPiJean\Flarum\Auth\SSOwat\Controllers;

use Flarum\User\AssertPermissionTrait;
use Flarum\User\Event\LoggedOut;
use Flarum\Foundation\Application;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Http\Exception\TokenMismatchException;
use Flarum\Http\Rememberer;
use Flarum\Http\SessionAuthenticator;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Http\Message\ServerRequestInterface as Request;
use Laminas\Diactoros\Response\RedirectResponse;
use Flarum\Settings\SettingsRepositoryInterface;

class SSOwatLogOutController implements ControllerInterface
{
    use AssertPermissionTrait;
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var Dispatcher
     */
    protected $events;
    /**
     * @var SessionAuthenticator
     */
    protected $authenticator;
    /**
     * @var Rememberer
     */
    protected $rememberer;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;
    /**
    * @param Application $app
    * @param Dispatcher $events
    * @param SessionAuthenticator $authenticator
    * @param Rememberer $rememberer
    * @param SettingsRepositoryInterface $settings
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
        $this->authenticator = $authenticator;
        $this->rememberer = $rememberer;
        $this->settings = $settings;
    }

    public function handle(Request $request)
    {
        // Prepare logging out of Flarum
        $session = $request->getAttribute('session');
        if (array_get($request->getQueryParams(), 'token') !== $session->get('csrf_token')) {
            throw new TokenMismatchException;
        }
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $url = array_get($request->getQueryParams(), 'return', $this->app->url());

        // Prepare redirection to SSOwat
        $ssowat = $this->settings->get('tituspijean-auth-ssowat.domain');
        $r = base64_encode($url);
        //$r = base64_encode("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $urlredirect = "https://" . $ssowat . "/yunohost/sso/?action=logout&r=" . $r;
        $response    = new RedirectResponse($urlredirect);

        // Logging out and redirecting
        $this->authenticator->logOut($session);
        $actor->accessTokens()->delete();
        $this->events->fire(new LoggedOut($actor));
        return $this->rememberer->forget($response);
    }
}
