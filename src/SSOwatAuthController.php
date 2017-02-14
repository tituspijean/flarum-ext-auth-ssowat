<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TitusPiJean\Flarum\Auth\SSOwat;

use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;

class SSOwatAuthController implements ControllerInterface
{
	/**
	 * @var AuthenticationResponseFactory
	 */
	protected $authResponse;

        /**
         * @param AuthenticationResponseFactory $authResponse
         * @param SettingsRepositoryInterface $settings
         */
        public function __construct( AuthenticationResponseFactory $authResponse, SettingsRepositoryInterface $settings )
        {
                $this->authResponse = $authResponse;
                $this->settings = $settings;
        }

	/**
	 * @param Request $request
	 * @return \Psr\Http\Message\ResponseInterface|RedirectResponse
	 */
	public function handle( Request $request )
	{
		$ssowat = $this->settings->get('flarum-ext-auth-ssowat.address');
		if ($ssowat == '') {
			return new Response("SSOwat domain is not set", 500);
		}
		if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
			$r           = base64_encode( "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			$urlredirect = "https://" . $ssowat . "/yunohost/sso/?r=" . $r;
			$response    = new RedirectResponse($urlredirect);
			$response    = $response->withHeader('Authorization', '');
			return $response;
		} else {
			$email = $request->getHeader('Email')[0];
			$uid = $_SERVER['PHP_AUTH_USER'];

			$identification = [
				'ssowat_id' => $uid,
			];
			$suggestions = [
				'username' => $uid,
				'email' => $email,
			];
			return $this->authResponse->make($request, $identification, $suggestions);
		}
	}
}
