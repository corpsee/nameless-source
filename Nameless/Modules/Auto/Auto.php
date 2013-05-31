<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Auto;

use Nameless\Modules\Auto\Providers\UserProviderInterface;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Auto class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Auto
{
	const ERROR_NONE             = 0;
	const ERROR_USERNAME_INVALID = 1;
	const ERROR_PASSWORD_INVALID = 2;
	const ERROR_UNKNOWN_IDENTITY = 100;

	/**
	 * @var integer
	 */
	protected $user_id;

	/**
	 * @var string
	 */
	protected $user_name;

	/**
	 * @var string
	 */
	protected $user_password;

	/**
	 * @var array
	 */
	protected $user_groups = array();

	/**
	 * @var UserProviderInterface
	 */
	protected $user_provider;

	/**
	 * @var int
	 */
	protected $error = self::ERROR_UNKNOWN_IDENTITY;

	/**
	 * @param UserProviderInterface $user_provider
	 */
	public function __construct(UserProviderInterface $user_provider, $user_name, $user_password)
	{
		$this->user_provider = $user_provider;
		$this->user_name     = $user_name;
		$this->user_password = $user_password;
	}

	/**
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * @return string
	 */
	public function getUserName()
	{
		return $this->user_name;
	}

	/**
	 * @return array
	 */
	public function getUserGroups()
	{
		return $this->user_groups;
	}

	/**
	 * @param string $user_name
	 * @param string $password
	 * @return integer
	 */
	public function authenticate()
	{
		$error = self::ERROR_NONE;
		$user = $this->user_provider->getUserByName($this->user_name);

		if (FALSE === $user)
		{
			$error = self::ERROR_USERNAME_INVALID;
		}
		else
		{
			if (!hashCheck($this->user_password, $user['password']))
			{
				$error = self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$this->user_id       = isset($user['id']) ? (integer)$user['id'] : $this->user_name;
				$this->user_groups   = $this->user_provider->getUserGroups($this->user_name);
			}
		}
		return $error;
	}
}