<?php

namespace Nameless\Tests\Core\Functions;

class FunctionTest extends \PHPUnit_Framework_TestCase
{

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
}