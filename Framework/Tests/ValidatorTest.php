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
			array('test+mail@test.com'),
			array('test..mail@test.com'),
			array('testmail@test.mail.com'),
			array('testmail@test.mail.mail.com'),
			array('testmail@test.mail'),
			array('test_mail@test.com'),
			array('test__mail@test.com'),
			array('test-mail@test.com'),
			array('test--mail@test.com'),
			array('testmail@test--test.test'),
			array('testmail@test__test.test'),
			array('testmail@test_test.com'),
			array('testmail@test-test.com'),
			array('тестоваяпочта@тест.рф'),
			array('тестовая.почта@тест.рф'),
			array('тестовая+почта@тест.рф'),
			array('тестовая..почта@тест.рф'),
			array('тестоваяпочта@тест.почта.рф'),
			array('тестоваяпочта@тест.почта.почта.рф'),
			array('тестоваяпочта@test.com'),
		);
	}

	public function emailFalseProvider ()
	{
		return array
		(
			array('testmail@test.t'),
			array('testmail@test.testmail'),
			array('test...mail@test.testmail'),
			array('test++mail@test.test'),
			array('testmail@test+test.test'),
			array('тестоваяпочта@тест.почта'),
			array('testmail.com'),
			array('testmail'),
			array('testmail@mail@mail.com'),
			array('test*mail@mail.com'),
			array('test:mail@mail.com'),
			array('test(ma)il@mail.com'),
			array('test{ma}il@mail.com'),
			array('test?mail@mail.com'),
			array('test/mail@mail.com'),
		);
	}

	/**
	 * @dataProvider emailTrueProvider
	 */
	public function testTrueEmail ($string)
	{
		$this->init();
		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', $string, array('email')), "Validator->validateField('email') for correct emails is CORRECT");
	}

	/**
	 * @dataProvider emailFalseProvider
	 */
	public function testFalseEmail ($string)
	{
		$this->init();
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', $string, array('email')), "Validator->validateField('email') for incorrect emails is CORRECT");
	}

	public function testNoempty ()
	{
		$this->init();

		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', 'непустое_поле', array('noempty')), "Validator->validateField('noempty') for correct noempty is CORRECT");
		$this->assertArrayNotHasKey(0, $this->validator->validateField('test_field', '.', array('noempty')), "Validator->validateField('noempty') for correct noempty is CORRECT");

		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', '', array('noempty')), "Validator->validateField('noempty') for incorrect noempty is CORRECT");
		$this->assertArrayHasKey(0, $this->validator->validateField('test_field', '  ', array('noempty')), "Validator->validateField('noempty') for incorrect noempty is CORRECT");
	}
}