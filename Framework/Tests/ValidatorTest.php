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

	public function testNoempty ()
	{
		$this->init();

		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', 'непустое_поле', array('noempty')));
		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', '.', array('noempty')));

		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', '', array('noempty')));
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', '  ', array('noempty')));
	}
}