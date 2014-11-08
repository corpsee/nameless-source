<?php

namespace Nameless\Tests\Tests\Core\Functions;

class FunctionTest extends \PHPUnit_Framework_TestCase
{
    public function pathToURLProvider()
    {
        return array
        (
            [PUBLIC_PATH . 'files/path/to/url'],
            [PUBLIC_PATH . 'files\path\to\url'],
        );
    }

    public function URLToPathProvider()
    {
        return array
        (
            ['/files/path/to/url'],
        );
    }

    public function stringToArrayProvider()
    {
        return array
        (
            ['string_part, string_part', 2],
            ['string part, string part', 2],
            ['string_part, string_part,', 2],
            ['string_part, string_part,,', 2],
            ['', 0],
            [',', 0],
            [' , ', 0],
        );
    }

    public function arrayToStringProvider()
    {
        return array
        (
            [['string', 'string'], 'string, string'],
            [['string'], 'string'],
            [[], ''],
        );
    }

    public function testHashMakeCheck()
    {
        $password = 'password_string';
        $hash = hashMake($password);
        $this->assertTrue(hashCheck($password, $hash));
    }

    /**
     * @dataProvider pathToURLProvider
     */
    public function testPathToURL($string)
    {
        $this->assertEquals('/files/path/to/url', pathToURL($string));
    }

    /**
     * @dataProvider URLToPathProvider
     */
    public function testURLToPath($string)
    {
        $this->assertEquals(PUBLIC_PATH . 'files/path/to/url', URLToPath($string));
    }


    /**
     * @dataProvider stringToArrayProvider
     */
    public function testStringToArray($string, $count)
    {
        $this->assertCount($count, stringToArray($string));
    }

    /**
     * @dataProvider arrayToStringProvider
     */
    public function testArrayToString($array, $string)
    {
        $this->assertEquals($string, arrayToString($array));
    }
}