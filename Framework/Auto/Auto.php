<?php

namespace Framework\Auto;

use Framework\Model;
use Framework\Auto\Provider\UserProviderInterface;
use Symfony\Component\HttpFoundation\Cookie;

class Auto
{
	const ERROR_NONE             = 0;
	const ERROR_USERNAME_INVALID = 1;
	const ERROR_PASSWORD_INVALID = 2;

	/**
	 * @var integer
	 */
	protected $user_id;

	/**
	 * @var string
	 */
	protected $user_name;

	/**
	 * @var array
	 */
	protected $user_groups = array();

	/**
	 * @var UserProviderInterface
	 */
	protected $user_provider;

	/**
	 * @param UserProviderInterface $user_provider
	 */
	public function __construct(UserProviderInterface $user_provider)
	{
		$this->user_provider = $user_provider;
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
	public function authenticate($user_name, $user_password)
	{
		$user = $this->user_provider->getUserByName($user_name);

		if (FALSE === $user)
		{
			$error = self::ERROR_USERNAME_INVALID;
		}
		else
		{
			if ($this->getUserHash($user_password, $user['salt']) !== $user['password'])
			{
				$error = self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$this->user_id       = (integer)$user['id'];
				$this->user_name     = $user_name;
				$this->user_groups   = $this->user_provider->getUserGroups($user_name);

				$error = self::ERROR_NONE;
			}
		}
		return $error;
	}

	public function getUserHash ($user_password, $user_salt)
	{
		sleep(2);
		return hash_hmac('sha1', $user_password, $user_salt);
	}


}