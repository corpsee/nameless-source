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

abstract class ImageDriver
{
	protected $width;

	protected $height;

	public abstract function open ($image_path);

	public abstract function create ($width, $height, $color = 0xffffff, $opacity = 0);

	public abstract function render ($format = 'png', $quality = 80);

	public abstract function save ($image_path, $format = NULL, $quality = 80);

	protected abstract function getPixel ($x, $y);

	protected abstract function getColor ($color, $opacity);

	protected abstract function destroy ();

	public abstract function crop ($width, $height, $x = 0, $y = 0);

	public abstract function scale ($scale);

	public abstract function rotate ($angle, $bg_color = 0xffffff, $bg_opacity = 0);

	public abstract function flip ($flip_x = FALSE, $flip_y = FALSE);

	public abstract function overlay ($layer, $x = 0, $y = 0);

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

	protected function getExtension ($image_path)
	{
		$extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
		if ($extension === 'jpg')
		{
			$extension = 'jpeg';
		}
		return $extension;
	}
}
