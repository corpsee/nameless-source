<?php

namespace Nameless\Tests\Modules\Tests\Modules\Validation;

use Nameless\Modules\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    private $validator;

    public function init()
    {
        $this->validator = new Validator();
    }

    public function noEmptyTrueProvider()
    {
        return [
            ['no_empty'],
            ['.'],
            [0],
            ['0']
        ];
    }

    public function noEmptyFalseProvider()
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
        self::assertEmpty($this->validator->validate($string, ['email_simple']));
    }

    /**
     * @dataProvider emailSimpleFalseProvider
     */
    public function testFalseEmailSimple($string)
    {
        $this->init();
        self::assertArrayHasKey(0, $this->validator->validate($string, ['email_simple']));
    }

    /**
     * @dataProvider emailTrueProvider
     */
    public function testTrueEmail($string)
    {
        $this->init();
        self::assertEmpty($this->validator->validate($string, ['email']));
    }

    /**
     * @dataProvider emailFalseProvider
     */
    public function testFalseEmail($string)
    {
        $this->init();
        self::assertArrayHasKey(0, $this->validator->validate($string, ['email']));
    }

    /**
     * @dataProvider noEmptyTrueProvider
     */
    public function testTrueNoEmpty($string)
    {
        $this->init();
        self::assertArrayNotHasKey(0, $this->validator->validate($string, ['no_empty']));
    }

    /**
     * @dataProvider noEmptyFalseProvider
     */
    public function testFalseNoEmpty($string)
    {
        $this->init();
        self::assertArrayHasKey(0, $this->validator->validate($string, ['no_empty']));
    }

    /**
     * @dataProvider numberTrueProvider
     */
    public function testTrueNumber($string)
    {
        $this->init();
        self::assertArrayNotHasKey(0, $this->validator->validate($string, ['number']));
    }

    /**
     * @dataProvider numberFalseProvider
     */
    public function testFalseNumber($string)
    {
        $this->init();
        self::assertArrayHasKey(0, $this->validator->validate($string, ['number']));
    }
}
