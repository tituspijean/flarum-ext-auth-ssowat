<?php namespace TitusPiJean\Flarum\Auth\SSOwat\Controllers;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\Http\Rememberer;
use Flarum\Http\SessionAuthenticator;
use Flarum\User\AssertPermissionTrait;
use Flarum\User\User;
use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class SSOwatLogInController implements RequestHandlerInterface
{
  protected $response;
  protected $settings;
  protected $url;

  public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
  {
      $this->response = $response;
      $this->settings = $settings;
      $this->url = $url;
  }

  public function handle(ServerRequestInterface $request): ResponseInterface
  {
      $redirectUri = $this->url->to('forum')->route('auth.ssowat');

        // Check if the SSOwat domain has been set
        $ssowat = $this->settings->get('tituspijean-auth-ssowat.domain');
        if ($ssowat == '') {
            // If not set, make user default login method is active
            $this->settings->set('tituspijean-auth-ssowat.onlyUse', false);
            return new TextResponse('SSOwat domain is not set, Flarum login has been reactivated.
Please configure SSOwat extension.', 500, []);
        }

        // Checking and retrieving credentials
        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            // If PHP credentials are not set, ask for it
            $r = base64_encode("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            $urlredirect = "https://" . $ssowat . "/yunohost/sso/?r=" . $r;
            $response    = new RedirectResponse($urlredirect);
            $response    = $response->withHeader('Authorization', '');
            return $response;
        } else {
            // Retrieve user information
            $email = $_SERVER['HTTP_EMAIL'];
            $name = $_SERVER['HTTP_NAME'];
            $uid = $_SERVER['PHP_AUTH_USER'];
            // Prepare Flarum id and suggestions
            $identification = [
                'username' => $uid,
                'email' => $email
            ];
            $suggestions = [
                'username' => $uid,
                'name' => $name,
                'email' => $email
            ];
            $user = User::where($identification)->first();
            $session = $request->getAttribute('session');
            if ($user) {
                // If user exists, then we expect them to be logged in
                $session->put('ssowatUser', 1);
            } else {
                // If user does not exist, then we at least set the session for after signing up
                $session->put('ssowatUser', 2);
            }
            $session->save();
            // Send credentials to Flarum it will either log in, or sign up
            return $this->response->make(
              'ssowat', $uid,
              function (Registration $registration) use ($user) {
                  $registration
                      ->provideTrustedEmail($user->email)
                      ->suggestUsername($user->getDisplayNameAttribute())
                      ->setPayload($user->toArray());
                }
            );
        }
    }
}
