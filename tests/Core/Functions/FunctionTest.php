<?php

namespace Nameless\Tests\Tests\Core\Functions;

class FunctionTest extends \PHPUnit_Framework_TestCase
{
    public function pathToURLProvider()
    {
        $unix_public = str_replace('\\', '/', PUBLIC_PATH);
        $win_public  = str_replace('/', '\\', PUBLIC_PATH);
        return [
            [$unix_public . 'files/path/to/url'],
            [$unix_public . 'files\path\to\url'],
            [$win_public . 'files/path/to/url'],
            [$win_public . 'files\path\to\url'],
        ];
    }

    public function URLToPathProvider()
    {
        return [
            ['/files/path/to/url'],
        ];
    }

    public function stringToArrayProvider()
    {
        return [
            ['string_part, string_part', 2],
            ['string part, string part', 2],
            ['string_part, string_part,', 2],
            ['string_part, string_part,,', 2],
            ['', 0],
            [',', 0],
            [' , ', 0],
        ];
    }

    public function arrayToStringProvider()
    {
        return [
            [['string', 'string'], 'string, string'],
            [['string'], 'string'],
            [[], ''],
        ];
    }

    /**
     * @dataProvider pathToURLProvider
     */
    public function testPathToURL($string)
    {
        self::assertEquals('/files/path/to/url', pathToURL($string));
    }

    /**
     * @dataProvider URLToPathProvider
     */
    public function testURLToPath($string)
    {
        $unix_public = str_replace('\\', '/', PUBLIC_PATH);

        self::assertEquals($unix_public . 'files/path/to/url', URLToPath($string));
    }


    /**
     * @dataProvider stringToArrayProvider
     */
    public function testStringToArray($string, $count)
    {
        self::assertCount($count, stringToArray($string));
    }

    /**
     * @dataProvider arrayToStringProvider
     */
    public function testArrayToString($array, $string)
    {
        self::assertEquals($string, arrayToString($array));
    }
}
