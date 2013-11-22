<?php

namespace Nameless\Tests\Core\Functions;

class FunctionTest extends \PHPUnit_Framework_TestCase
{
	public function pathToURLProvider ()
	{
		return array
		(
			array(PUBLIC_PATH . 'files/path/to/url'),
			array(PUBLIC_PATH . 'files' . DS . 'path' . DS . 'to' . DS . 'url'),
			array(PUBLIC_PATH . 'files\path\to\url'),
		);
	}

	public function URLToPathProvider ()
	{
		return array
		(
			array('/files/path/to/url'),
		);
	}

	public function stringToArrayProvider ()
	{
		return array
		(
			array('string_part, string_part', 2),
			array('string part, string part', 2),
			array('string_part, string_part,', 2),
			array('string_part, string_part,,', 2),
			array('', 0),
			array(',', 0),
			array(' , ', 0),
		);
	}

	public function arrayToStringProvider ()
	{
		return array
		(
			array(array('string', 'string'), 'string, string'),
			array(array('string'), 'string'),
			array(array(), ''),
		);
	}

	public function testHashMakeCheck ()
	{
		$password = 'password_string';
		$hash = hashMake($password);
		$this->assertTrue(hashCheck($password, $hash));
	}

	/**
	 * @dataProvider pathToURLProvider
	 */
	public function testPathToURL ($string)
	{
		$this->assertEquals('/files/path/to/url', pathToURL($string));
	}

	/**
	 * @dataProvider URLToPathProvider
	 */
	public function testURLToPath ($string)
	{
		$this->assertEquals(PUBLIC_PATH . 'files' . DS . 'path' . DS . 'to' . DS . 'url', URLToPath($string));
	}


	/**
	 * @dataProvider stringToArrayProvider
	 */
	public function testStringToArray ($string, $count)
	{
		$this->assertCount($count, stringToArray($string));
	}

	/**
	 * @dataProvider arrayToStringProvider
	 */
	public function testArrayToString ($array, $string)
	{
		$this->assertEquals($string, arrayToString($array));
	}
}