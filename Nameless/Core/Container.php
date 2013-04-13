<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

/**
 * Dependency injection container
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Container
{
	/**
	 * @var array
	 */
	protected $values = array();

	/**
	 * @param string $key
	 *
	 * @param mixed $value
	 */
	public function __set ($key, $value)
	{
		$this->values[$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __get ($key)
	{
		if (!isset($this->values[$key]) && !is_null($this->values[$key]))
		{
			throw new \InvalidArgumentException(sprintf('Value "%s" is not defined in container.', $key));
		}

		if (is_callable($this->values[$key]))
		{
			return $this->values[$key]($this);
		}
		else
		{
			return $this->values[$key];
		}
	}

	/**
	 * @param callable $closure
	 *
	 * @return callable
	 */
	public function service (\Closure $closure)
	{
		return function ($c) use ($closure)
		{
			static $instance;

			if (is_null($instance))
			{
				$instance = $closure($c);
			}

			return $instance;
		};
	}
}