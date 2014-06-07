<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Imager;

//TODO: effects: brightness, contrast, blur, smooth
//TODO: driver for Imagick
abstract class ImageDriver
{
	protected $width;

	protected $height;

	protected $mime_type = 'undefined';

	abstract public function open ($image_path);

	abstract public function create ($width, $height, $color = '#FFF', $opacity = 0);

	abstract public function render ($format = 'image/jpeg', $quality = 80);

	abstract public function save ($image_path, $format = NULL, $quality = 80);

	abstract public function crop ($width, $height, $x = 0, $y = 0);

	abstract public function scale ($scale);

	abstract public function rotate ($angle, $bg_color = '#FFF', $bg_opacity = 0);

	abstract public function flip ($flip_x = FALSE, $flip_y = FALSE);

	abstract public function overlay ($layer, $x = 0, $y = 0);

	abstract public function gamma ($correction);

	abstract public function negative ();

	abstract public function grayscale ();

	abstract public function colorize ($color, $opacity = 0);

	protected abstract function destroy ();

	public function __destruct ()
	{
		$this->destroy();
	}

	public function resize ($width = NULL, $height = NULL, $fit = TRUE)
	{
		if ($width && $height)
		{
			$scale_w = $width / $this->width;
			$scale_h = $height / $this->height;
			$scale   = ($fit && $scale_w > $scale_h) ? $scale_h : $scale_w;
		}
		elseif ($width)
		{
			$scale = $width / $this->width;
		}
		elseif ($height)
		{
			$scale = $height / $this->height;
		}
		else
		{
			throw new \LogicException("Either width or height must be set");
		}
		$this->scale($scale);

		return $this;
	}

	public function fill ($width, $height)
	{
		$this->resize($width, $height, FALSE);

		$x = ($this->width - $width) / 2;
		$y = ($this->height - $height) / 2;

		$this->crop($width, $height, $x, $y);

		return $this;
	}

	public function getExtension ($image_path)
	{
		$extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
		if ($extension === 'jpg')
		{
			$extension = 'jpeg';
		}
		return $extension;
	}

	protected function normalizeColor ($color)
	{
		$color = strtolower(ltrim($color, '#'));
		if (3 !== strlen($color) && 6 !== strlen($color) && 0 === preg_match('#[0-9a-f]{3,6}#i', $color))
		{
			throw new \InvalidArgumentException('Invalid color argument');
		}
		if (strlen($color) === 3)
		{
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}
		return array_map('hexdec', str_split($color, 2));
	}
}
