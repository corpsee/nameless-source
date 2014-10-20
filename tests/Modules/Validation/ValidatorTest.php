<?php

namespace Nameless\Tests\Modules\Tests\Modules\Validation;

use Nameless\Modules\Validation\Validator;
use Pimple\Container;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    private $validator;

    public function init()
    {
        $container = new Container();
        $this->validator = new Validator($container);
    }

    public function noemptyTrueProvider()
    {
        return [
            ['noempty'],
            ['.'],
        ];
    }

    public function noemptyFalseProvider()
    {
        return [
            [''],
            [' '],
        ];
    }

    public function numberTrueProvider()
    {
        return [
            ['0'],
            ['12569'],
        ];
    }

    public function numberFalseProvider()
    {
        return [
            ['12.569'],
            ['12 569'],
            ['-12569'],
            ['12569a'],
        ];
    }

    public function emailTrueProvider()
    {
        return [
            ['testmail@test.com'],
            ['test.mail@test.com'],
            ['test..mail@test.com'],
            ['test+mail@test.com'],
            ['test++mail@test.com'],
            ['test-mail@test.com'],
            ['test--mail@test.com'],
            ['test_mail@test.com'],
            ['test__mail@test.com'],
            ['testmail@testmail'],
            ['testmail@testmail.testmail'],
            ['testmail@test-mail.com'],
            ['testmail@test--mail.com'],
            ['testmail@тестовая.почта'],
        ];
    }

    public function emailFalseProvider()
    {
        return [
            ['-testmail@mail.com'],
            ['testmail@-mail.com'],
            ['testmail@mail.-com'],
            ['testmail@test@mail.com'],
            ['testmail@test.mail.com'],
            ['testmail@test..mail'],
            ['testmail@test_mail.com'],
            ['testmail@test+mail.com'],
            ['тестоваяпочта@тестовая.почта'],
        ];
    }

    public function emailSimpleTrueProvider()
    {
        return [
            ['testmail@test.com'],
            ['test.mail@test.com'],
            ['test..mail@test.com'],
            ['test+mail@test.com'],
            ['test++mail@test.com'],
            ['test-mail@test.com'],
            ['test--mail@test.com'],
            ['test_mail@test.com'],
            ['test__mail@test.com'],
            ['testmail@testmail'],
            ['testmail@testmail.testmail'],
            ['testmail@test-mail.com'],
            ['testmail@test--mail.com'],
            ['testmail@тестовая.почта'],
            ['-testmail@mail.com'],
            ['testmail@-mail.com'],
            ['testmail@mail.-com'],
            ['testmail@test.mail.com'],
            ['testmail@test..mail'],
            ['testmail@test_mail.com'],
            ['testmail@test+mail.com'],
            ['тестоваяпочта@тестовая.почта'],
        ];
    }

    public function emailSimpleFalseProvider()
    {
        return [
            ['testmail_test.com'],
            ['testmail@test@mail.com'],
        ];
    }

    /**
     * @dataProvider emailSimpleTrueProvider
     */
    public function testTrueEmailSimple($string)
    {
        $this->init();
        $this->assertEmpty($this->validator->validateFieldTest($string, ['email_simple']));
    }

    /**
     * @dataProvider emailSimpleFalseProvider
     */
    public function testFalseEmailSimple($string)
    {
        $this->init();
        $this->assertArrayHasKey(0, $this->validator->validateFieldTest($string, ['email_simple']));
    }

    /**
     * @dataProvider emailTrueProvider
     */
    public function testTrueEmail($string)
    {
        $this->init();
        $this->assertEmpty($this->validator->validateFieldTest($string, ['email']));
    }

    /**
     * @dataProvider emailFalseProvider
     */
    public function testFalseEmail($string)
    {
        $this->init();
        $this->assertArrayHasKey(0, $this->validator->validateFieldTest($string, ['email']));
    }

    /**
     * @dataProvider noemptyTrueProvider
     */
    public function testTrueNoempty($string)
    {
        $this->init();
        $this->assertArrayNotHasKey(0, $this->validator->validateFieldTest($string, ['noempty']));
    }

    /**
     * @dataProvider noemptyFalseProvider
     */
    public function testFalseNoempty($string)
    {
        $this->init();
        $this->assertArrayHasKey(0, $this->validator->validateFieldTest($string, ['noempty']));
    }

    /**
     * @dataProvider numberTrueProvider
     */
    public function testTrueNumber($string)
    {
        $this->init();
        $this->assertArrayNotHasKey(0, $this->validator->validateFieldTest($string, ['number']));
    }

    /**
     * @dataProvider numberFalseProvider
     */
    public function testFalseNumber($string)
    {
        $this->init();
        $this->assertArrayHasKey(0, $this->validator->validateFieldTest($string, ['number']));
    }
}