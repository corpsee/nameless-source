<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework\Validation;

class Validator
{
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var array
	 */
	private $errors = array
	(
		'noempty'     => 'The field "%s" can\'t be empty',
		'number'      => 'The field "%s" isn\'t a number',
		'decimal'     => 'The field "%s" isn\'t a decimal',
		'alpha'       => 'The field "%s" may only contain alphabetical characters',
		'alpha_ext'   => 'The field "%s" isn\'t a extend alphabetic',
		'email'       => 'The field "%s" isn\'t a valid url',
		'phone'       => 'The field "%s" isn\'t a valid phone number',
		'url'         => 'The field "%s" isn\'t a valid url',
		'length'      => 'The field "%s" must contain %d characters',
		'min_lehgth'  => 'The field "%s" must contain at least %d characters',
		'max_length'  => 'The field "%s" can\'t contain more than %d characters',
		'equal_field' => 'The field "%s" and the field "%s" must be equal',

		/*'noempty'     => 'Поле %s не может быть пустым',
		'number'      => 'Поле %s не является целым числом',
		'decimal'     => 'Поле %s не является десятичным числом',
		'alpha'       => 'Поле %s не является десятичным числом',
		'alpha_ext'   => 'Поле %s не является десятичным числом',
		'email'       => 'Поле %s не является почтой',
		'phone'       => 'Поле %s не является целым числом',
		'url'         => 'Поле %s не является url',
		'length'      => 'Поле %s не может содержать не %s символов',
		'min_lehgth'  => 'Поле %s не может содержать меньше %s символов',
		'max_length'  => 'Поле %s не может содержать больше %s символов',
		'equal_field' => 'Поле %s должно быть идентичным полю %s',*/
	);

	/**
	 * @param Container $container
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function noempty ($value)
	{
		if (!$value) { return FALSE; }
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function number ($value)
	{
		if ($value && !preg_match('!^[\p{Nd}]+$!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function decimal ($value)
	{
		if ($value && !preg_match('!^[\p{Nd}\., ]+$!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function alpha ($value)
	{
		if ($value && !preg_match('!^[\p{L} ]+$!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function alpha_ext ($value)
	{
		if ($value && !preg_match('!^[\p{L}\p{P}\p{Nd} ]+$!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function email ($value)
	{
		if ($value && !preg_match('!
			^[a-zA-Z0-9][-_+\.a-zA-Z0-9]*                      # имя почты (может включать .-+_)
			@                                    # разделитель
			[\p{L}\p{Nd}][-\p{L}\p{Nd}]*         # домменое имя без зоны (любое кол-во поддоменов)
			([\.][\p{L}\p{Nd}][-\p{L}\p{Nd}]*)?$ # доменная зона
		!xiu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function phone ($value)
	{
		if ($value && !preg_match('!^[\p{Nd}+\(\) ]+$!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 *
	 * @return boolean
	 */
	private function url ($value)
	{
		if ($value && !preg_match('!^(http://|https://){0,1}(www\.){0,1}([-\p{L}\p{Nd}_]{2,}){1,}\.[\p{L}\p{Nd}]{2,4}[-\p{L}\p{Nd}_+&/=?\.%]{1,}!iu', $value))
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 * @param string $rule
	 *
	 * @return boolean
	 */
	private function length ($value, $rule)
	{
		if (mb_strlen($value, 'UTF-8') !== $rule)
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 * @param string $rule
	 *
	 * @return boolean
	 */
	private function min_length ($value, $rule)
	{
		if (mb_strlen($value, 'UTF-8') < $rule)
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 * @param string $rule
	 *
	 * @return boolean
	 */
	private function max_length ($value, $rule)
	{
		if (mb_strlen($value, 'UTF-8') > $rule)
		{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * @param string $value
	 * @param string $rule
	 *
	 * @return boolean
	 */
	private function equal_field ($value, $rule)
	{
		if ($value !== $rule)
		{
			return FALSE;
		}
		return TRUE;
	}

	//TODO: private
	/**
	 * @param string $key
	 * @param string $value
	 * @param array $rules
	 *
	 * @return array
	 *
	 * @throws \InvalidArgumentException
	 */
	public function validateField ($key, $value, $rules)
	{
		$errors = array();
		$value = trim($value);

		foreach ($rules as $rule)
		{
			if (is_array($rule) && count($rule) == 2)
			{
				if (!$error = $this->{$rule[0]}($value))
				{
					$errors[] = sprintf($this->errors[$rule[0]], $key, $rule[1]);
				}
			}
			elseif (is_string($rule))
			{
				if (!$error = $this->{$rule}($value))
				{
					$errors[] = sprintf($this->errors[$rule], $key);
				}
			}
			else
			{
				throw new \InvalidArgumentException('Invalid validation rule');
			}
		}

		return $errors;
	}

	/**
	 * @param string $form
	 *
	 * @return array
	 *
	 * @throws \InvalidArgumentException
	 */
	public function validate ($form)
	{
		$errors = array();
		if (isset($this->container['validation_rules'][$form]))
		{
			$post = $this->container['request']->request->all();
			foreach ($post as $key => $value)
			{
				if (isset($this->container['validation_rules'][$form][$key]))
				{
					if ($validate = $this->validateField($key, $value, $this->container['validation_rules'][$form][$key]))
					{
						$errors[] = $validate;
					}
				}
			}
		}
		else
		{
			throw new \InvalidArgumentException('Invalid form name');
		}
		return $errors;
	}
}