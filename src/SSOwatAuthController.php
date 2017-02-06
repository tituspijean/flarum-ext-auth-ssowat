<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarum\Auth\SSOwat;

use Flarum\Forum\AuthenticationResponseFactory;
use Flarum\Http\Controller\ControllerInterface;
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
	 */
	public function __construct( AuthenticationResponseFactory $authResponse )
	{
		$this->authResponse = $authResponse;
	}

	/**
	 * @param Request $request
	 * @return \Psr\Http\Message\ResponseInterface|RedirectResponse
	 */
	public function handle( Request $request )
	{
		if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
			return new Response("Authentification error", 500);
		} else {
			$uid = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			$identification = [
				'username' => $uid
			];
			$config = [
			    'domain_controllers'    => array('localhost'),
			    'base_dn'               => 'ou=users,dc=yunohost,dc=org',
			    'port'                  => 389,
			    'timeout'               => 5,
			];
			$provider = new \Adldap\Connections\Provider($config);
			$uid_dn='uid='.$uid.','.$config['base_dn'];
			try {
			    if ($provider->auth()->attempt($uid_dn, $password, $bindAsUser = true)) {
				$user = $provider->search()->findBy('uid', $uid);
				$suggestions = [
					'username' => $uid,
					'email' => $user->mail[0]
				];
				return $this->authResponse->make($request, $identification, $suggestions);
				}
			} catch (\Adldap\Exceptions\Auth\UsernameRequiredException $e) {
				return new Response("No username", 500);
			} catch (\Adldap\Exceptions\Auth\PasswordRequiredException $e) {
				return new Response("No password", 500);
			} catch (\Adldap\Exceptions\Auth\BindException $e) {
				return new Response("Could not bind", 500);
			}
			return new Response("Error", 500);
		}
	}
}
