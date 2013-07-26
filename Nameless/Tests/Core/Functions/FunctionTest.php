<?php

namespace Nameless\Tests\Core\Functions;

class FunctionTest extends \PHPUnit_Framework_TestCase
{
	public function hashMakeCheckProvider ()
	{
		return array
		(
			array(FILE_PATH . 'password'),
		);
	}

	public function pathToURLProvider ()
	{
		return array
		(
			array(FILE_PATH . 'path/to/url'),
			array(FILE_PATH . 'path' . DS . 'to' . DS . 'url'),
			array(FILE_PATH . 'path\to\url'),
		);
	}

	public function URLToPathProvider ()
	{
		return array
		(
			array(FILE_PATH_URL . 'path/to/url'),
		);
	}

	public function stringToArrayProvider ()
	{
		return array
		(
			array('string_part, string_part'),
			array('string part, string part'),
			array('string_part, string_part,'),
			array('string_part, string_part,,'),
			array(''),
			array(','),
			array(' , '),
		);
	}

	public function arrayToStringProvider ()
	{
		return array
		(
			array(array('string', 'string')),
			array(array('string',)),
			array(array()),
		);
	}

	/**
	 * @dataProvider hashMakeCheckProvider
	 */
	public function testHashMakeCheck ($string)
	{
		$hash = hashMake($string);
		$this->assertTrue(hashCheck($string, $hash));
	}

	/**
	 * @dataProvider pathToURLProvider
	 */
	public function testPathToURL ($string)
	{
		$this->assertEquals(FILE_PATH_URL . 'path/to/url', pathToURL($string));
	}

	/**
	 * @dataProvider URLToPathProvider
	 */
	public function testURLToPath ($string)
	{
		$this->assertEquals(FILE_PATH . 'path' . DS . 'to' . DS . 'url', URLToPath($string));
	}


	/**
	 * @dataProvider stringToArrayProvider
	 */
	public function testStringToArray ($string)
	{

	}


	/**
	 * @dataProvider arrayToStringProvider
	 */
	public function testArrayToString (array $array)
	{

	}
}