<?php

namespace Framework\Auto;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouteCollection;

class User
{
	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var array
	 */
	private $routes;

	/**
	 * @var array
	 */
	private $access;

	const USER_ID       = 'user_id';
	const USER_NAME     = 'user_name';
	const USER_GROUPS   = 'user_groups';

	/**
	 * @param Session $session
	 * @param RouteCollection $routes
	 * @param array $access
	 */
	public function __construct(Session $session, RouteCollection $routes, array $access)
	{
        $this->session = $session;
        $this->routes = $routes;
        $this->access = $access;
	}

    /**
	 * @param Auto $auto
	 * @param integer $duration
	 *
	 * @return Response|void
	 */
	public function login(Auto $auto, $duration = 0/*, Response $response = NULL, $duration = 0*/)
	{
		$user_groups   = serialize($auto->getUserGroups());
		$user_id       = $auto->getUserId();
		$user_name     = $auto->getUserName();

		$this->session->migrate(FALSE, $duration);

		$this->session->set(self::USER_ID, $user_id);
		$this->session->set(self::USER_NAME, $user_name);
		$this->session->set(self::USER_GROUPS, $user_groups);

		/*if (($response instanceof Response) && ($duration > 0))
		{
			$cookie_array = array
			(
				self::USER_ID        => $user_id,
				self::USER_NAME      => $user_name,
				self::USER_PASSWORD  => $user_password,
			);

			$cookie = array
			(
					self::USER_ID       => $user_id,
					self::USER_NAME     => $user_name,
					self::USER_DURATION => $duration,
					self::USER_PREVIEW  => sha1(serialize($cookie_array)),
			);

			$response->headers->setCookie(new Cookie(self::COOKIE_AUTOLOGIN, serialize($cookie), time() + $duration));

			return $response;
		}*/
	}

	/*public function autoLogin(Auto $auto)
	{
		$user_groups   = arrayToString($auto->getUserGroups());
		$user_id       = $auto->getUserId();
		$user_name     = $auto->getUserName();

		$this->session->set(self::USER_ID, $user_id);
		$this->session->set(self::USER_NAME, $user_name);
		$this->session->set(self::USER_GROUPS, $user_groups);
	}*/

	/**
	 * @param boolean $destroy
	 *
	 * @return boolean
	 */
	public function logout($destroy = FALSE)
	{
		if ($destroy === TRUE)
		{
			$this->session->invalidate();
		}
		else
		{
			$this->session->remove(self::USER_ID);
			$this->session->remove(self::USER_NAME);
			$this->session->remove(self::USER_GROUPS);

			$this->session->migrate();
		}

		return !$this->isLogin();
	}

	/**
	 * @param integer|null $default
	 *
	 * @return integer|null
	 */
	public function getUserId ($default = NULL)
	{
		return (integer)$this->session->get(self::USER_ID, $default);
	}

	/**
	 * @param string|null $default
	 *
	 * @return string|null
	 */
	public function getUserName ($default = NULL)
	{
		return $this->session->get(self::USER_NAME, $default);
	}

	/**
	 * @param array $default
	 *
	 * @return array
	 */
	public function getUserGroups (array $default = array())
	{
		return unserialize($this->session->get(self::USER_GROUPS, serialize($default)));
	}

	/**
	 * @return boolean
	 */
	public function isLogin ()
	{
		return ($this->getUserName() !== NULL);
	}

	/**
	 * @param string $route
	 *
	 * @return boolean
	 */
	public function getAccess ($route)
	{
		//TODO: переименовать в getAccessByRoute
		$defaults = $this->routes->get($route)->getDefaults();

		return $this->getAccessByController($defaults['_controller']);
	}

	/**
	 * @param string $controller
	 *
	 * @return boolean
	 */
	public function getAccessByController ($controller)
	{
		$access = FALSE;
		list($controller, $action) = explode('::', $controller);

		$groups = $this->getUserGroups();

		//echo '<pre>';
		//print_r(array($controller, $action));
		//print_r($this->access); exit;

		// если в настройках нет контроллера - разрешен
		if (!isset($this->access[$controller]))
		{
			//echo 1;
			$access = TRUE;
		}
		else
		{
			//echo 2;
			// если в настройках нет действия - разрешен
			if(!isset($this->access[$controller][$action]))
			{
				echo 3;
				$access = TRUE;
			}
			else
			{
				//echo 4;
				//echo '<pre>'; print_r($groups);
				foreach ($this->access[$controller][$action] as $action_access)
				{
					if (in_array($action_access, $groups))
					{
						//echo 5;
						$access = TRUE;
					}
				}
			}
		}
		//exit;
		return $access;
	}
}