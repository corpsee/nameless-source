<?php

namespace Framework\Tests;

define('ROOT_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'Constants.php';
require_once ROOT_PATH . 'Functions.php';

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