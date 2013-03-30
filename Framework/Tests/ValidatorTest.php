<?php

namespace Framework\Tests;

use Framework\Validator;
use Framework\Container;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Validator
	 */
	private $validator;

	public function init()
	{
		$this->validator = new Validator(new Container());
	}

	public function noemptyTrueProvider ()
	{
		return array
		(
			array('noempty'),
			array('.'),
		);
	}

	public function noemptyFalseProvider ()
	{
		return array
		(
			array(''),
			array(' '),
		);
	}

	public function numberTrueProvider ()
	{
		return array
		(
			array('0'),
			array('12569'),
		);
	}

	public function numberFalseProvider ()
	{
		return array
		(
			array('12.569'),
			array('12 569'),
			array('-12569'),
			array('12569a'),
		);
	}

	public function emailTrueProvider ()
	{
		return array
		(
			array('testmail@test.com'),
			array('test.mail@test.com'),
			array('test...mail@test.com'),
			array('test+mail@test.com'),
			array('test+++mail@test.com'),
			array('test-mail@test.com'),
			array('test---mail@test.com'),
			array('test_mail@test.com'),
			array('test___mail@test.com'),
			array('testmail@testmail'),
			array('testmail@testmail.testmail'),
			array('testmail@test-mail.com'),
			array('testmail@test---mail.com'),
			array('testmail@тестовая.почта'),
		);
	}

	public function emailFalseProvider ()
	{
		return array
		(
			array('-testmail@mail.com'),
			array('testmail@-mail.com'),
			array('testmail@mail.-com'),
			array('testmail@test@mail.com'),
			array('testmail@test.mail.com'),
			array('testmail@test..mail'),
			array('testmail@test_mail.com'),
			array('testmail@test+mail.com'),
			array('тестоваяпочта@тестовая.почта'),
		);
	}

	/**
	 * @dataProvider emailTrueProvider
	 */
	public function testTrueEmail ($string)
	{
		$this->init();
		//print_r($this->validator->validateField('test_field', $string, array('email'))); exit;
		$this->assertEmpty($this->validator->validateField('test_field', $string, array('email')));
	}

	/**
	 * @dataProvider emailFalseProvider
	 */
	public function testFalseEmail ($string)
	{
		$this->init();
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', $string, array('email')));
	}

	/**
	 * @dataProvider noemptyTrueProvider
	 */
	public function testTrueNoempty ($string)
	{
		$this->init();
		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', $string, array('noempty')));
	}

	/**
	 * @dataProvider noemptyFalseProvider
	 */
	public function testFalseNoempty ($string)
	{
		$this->init();
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', $string, array('noempty')));
	}

	/**
	 * @dataProvider numberTrueProvider
	 */
	public function testTrueNumber ($string)
	{
		$this->init();
		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', $string, array('number')));
	}

	/**
	 * @dataProvider numberFalseProvider
	 */
	public function testFalseNumber ($string)
	{
		$this->init();
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', $string, array('number')));
	}
}