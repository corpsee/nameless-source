<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Imager;

class Image
{
	protected $driver;

	public function __construct (ImageDriver $driver)
	{
		$this->driver = $driver;
	}

	public function open ($file_path)
	{
		return $this->driver->open($file_path);
	}

	public function create ($width, $height, $color = '#FFF', $opacity = 0)
	{
		return $this->driver->create($width, $height, $color, $opacity);
	}
}
