<?php namespace TitusPiJean\Flarum\Auth\SSOwat\Controllers;

use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\TextResponse;

class SSOwatLogInController implements ControllerInterface
{
    /**
     * @var AuthenticationResponseFactory
     */
    protected $authResponse;

    /**
     * @param AuthenticationResponseFactory $authResponse
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(AuthenticationResponseFactory $authResponse, SettingsRepositoryInterface $settings)
    {
        $this->authResponse = $authResponse;
        $this->settings = $settings;
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface|RedirectResponse
     */
    public function handle(Request $request)
    {
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
            $email = $request->getHeader('Email')[0];
            $uid = $_SERVER['PHP_AUTH_USER'];
            // Prepare Flarum id and suggestions
            $identification = [
                'username' => $uid,
                'email' => $email
            ];
            $suggestions = [
                'username' => $uid,
                'email' => $email
            ];
            // Store in session that the user logged in with SSOwat
            $session = $request->getAttribute('session');
            $session->set('ssowatUser', true);
            $session->save();
            // Send credentials to Flarum it will either log in, or sign up
            return $this->authResponse->make($request, $identification, $suggestions);
        }
    }
}
