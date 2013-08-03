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

abstract class GDDriver extends ImageDriver
{
	public $image;

	public function open ($image_path)
	{
		$image_size = getimagesize($image_path);

		if (FALSE === $image_size)
		{
			throw new \RuntimeException("File isn`t a valid image");
		}

		switch ($image_size['mime'])
		{
			case 'image/jpeg':
				$image = imagecreatefromjpeg($image_path);
				break;
			case 'image/png':
				$image = imagecreatefrompng($image_path);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($image_path);
				break;
			default:
				throw new \LogicException("Image mime type '" . $image_size['mime'] . "' don`t support");
		}

		imagealphablending($image, FALSE);
		$this->setImage($image, $image_size[0], $image_size[1]);

		return $this;
	}

	public function create ($width, $height, $color = '#FFF', $opacity = 0)
	{
		$image = $this->createGD($width, $height, $color, $opacity);
		$this->setImage($image, $width, $height);
		imagefilledrectangle($image, 0, 0, $width, $height, $this->getColor($color, $opacity));

		return $this;
	}

	public function render ($format = 'jpeg', $quality = 80)
	{
		switch ($format)
		{
			case 'jpeg':
				header('Content-Type: image/jpeg');
				$background = $this->getJPGBackground();
				imagejpeg($background, NULL, $quality);
				imagedestroy($background);
				break;
			case 'png':
				header('Content-Type: image/png');
				imagesavealpha($this->image, TRUE);
				imagepng($this->image);
				break;
			case 'gif':
				header('Content-Type: image/gif');
				imagegif($this->image);
				break;
			default:
				throw new \LogicException("Image type '" . $format . "' don`t support");
		}
	}

	public function save ($image_path, $format = NULL, $quality = 80)
	{
		if (is_null($format))
		{
			$format = $this->getExtension($image_path);
		}

		switch ($format)
		{
			case 'jpeg':
				$background = $this->getJPGBackground();
				imagejpeg($background, $image_path, $quality);
				imagedestroy($background);
				break;
			case 'png':
				imagesavealpha($this->image, TRUE);
				imagepng($this->image, $image_path);
				break;
			case 'gif':
				imagegif($this->image, $image_path);
				break;
			default:
				throw new \LogicException("Image type '" . $format . "' don`t support");
		}
		return $this;
	}

	public function crop ($width, $height, $x = 0, $y = 0)
	{
		if ($width > ($max_width = $this->width - $x))
			$width = $max_width;

		if ($height > ($max_height = $this->height - $y))
			$height = $max_height;

		$cropped = $this->createGD($width, $height);
		imagecopy($cropped, $this->image, 0, 0, $x, $y, $width, $height);
		$this->setImage($cropped, $width, $height);

		return $this;
	}

	public function scale ($scale)
	{
		$width  = ceil($this->width * $scale);
		$height = ceil($this->height * $scale);

		$resized = $this->createGD($width, $height);
		imagecopyresampled($resized, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
		$this->setImage($resized, $width, $height);

		return $this;
	}

	public function rotate ($angle, $bg_color = '#FFF', $bg_opacity = 0)
	{
		$rotated = imagerotate($this->image, $angle, $this->getColor($bg_color, $bg_opacity));
		imagealphablending($rotated, FALSE);
		$this->setImage($rotated, imagesx($rotated), imagesy($rotated));

		return $this;
	}

	public function flip ($flip_x = FALSE, $flip_y = FALSE)
	{
		if (!$flip_x && !$flip_y)
		{
			return $this;
		}

		$x = $flip_x ? $this->width - 1 : 0;;
		$width = ($flip_x ? -1 : 1) * $this->width;

		$y = $flip_y ? $this->height - 1 : 0;;
		$height = ($flip_y ? -1 : 1) * $this->height;

		$flipped = $this->createGD($this->width, $this->height);
		imagecopyresampled($flipped, $this->image, 0, 0, $x, $y, $this->width, $this->height, $width, $height);
		$this->setImage($flipped, $this->width, $this->height);

		return $this;
	}

	public function overlay ($layer, $x = 0, $y = 0)
	{
		imagealphablending($this->image, TRUE);
		imagecopy($this->image, $layer->image, $x, $y, 0, 0, $layer->width, $layer->height);
		imagealphablending($this->image, FALSE);
		return $this;
	}

	public function gamma ($correction)
	{
		if (FALSE === imagegammacorrect($this->image, 1.0, $correction))
		{
			throw new \RuntimeException('Failed to apply gamma correction to the image');
		}
		return $this;
	}

	public function negative ()
	{
		if (FALSE === imagefilter($this->image, IMG_FILTER_NEGATE))
		{
			throw new \RuntimeException('Failed to negate the image');
		}
		return $this;
	}

	public function grayscale ()
	{
		if (FALSE === imagefilter($this->image, IMG_FILTER_GRAYSCALE))
		{
			throw new \RuntimeException('Failed to grayscale the image');
		}
		return $this;
	}

	public function colorize ($color)
	{
		$color = $this->normalizeColor($color);
		if (FALSE === imagefilter($this->image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]))
		{
			throw new \RuntimeException('Failed to colorize the image');
		}
		return $this;
	}

	protected function setImage ($image, $width, $height)
	{
		if ($this->image)
		{
			imagedestroy($this->image);
		}
		$this->image  = $image;
		$this->width  = $width;
		$this->height = $height;
	}

	protected function createGD ($width, $height)
	{
		$image = imagecreatetruecolor($width, $height);
		imagealphablending($image, FALSE);
		return $image;
	}

	protected function getColor ($color, $opacity)
	{
		$color = $this->normalizeColor($color);
		return imagecolorallocatealpha($this->image, $color[0], $color[1], $color[2], 127 * (1 - $opacity));
	}

	protected function getJPGBackground ()
	{
		$background = $this->createGD($this->width, $this->height);
		imagefilledrectangle($background, 0, 0, $this->width, $this->height, $this->getColor('#FFF', 1));
		imagealphablending($background, TRUE);
		imagecopy($background, $this->image, 0, 0, 0, 0, $this->width, $this->height);
		imagealphablending($background, FALSE);

		return $background;
	}

	protected function destroy ()
	{
		imagedestroy($this->image);
		unset($this->image);
	}
}
