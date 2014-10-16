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

class GDDriver extends ImageDriver
{
    /**
     * @var resource
     */
    public $image_handler;

    /**
     * @param string $image_path
     *
     * @return $this
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function open($image_path)
    {
        $image_size = getimagesize($image_path);

        if (false === $image_size) {
            throw new \RuntimeException("File isn`t a valid image");
        }

        switch ($image_size['mime']) {
            case 'image/jpeg':
                $image_handler = imagecreatefromjpeg($image_path);
                break;
            case 'image/png':
                $image_handler = imagecreatefrompng($image_path);
                break;
            case 'image/gif':
                $image_handler = imagecreatefromgif($image_path);
                break;
            default:
                throw new \LogicException("Image mime type '" . $image_size['mime'] . "' don`t support");
        }

        imagealphablending($image_handler, false);
        $this->setImage($image_handler, $image_size[0], $image_size[1]);
        $this->mime_type = $image_size['mime'];

        return $this;
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param string $color
     * @param integer $opacity
     *
     * @return $this
     */
    public function create($width, $height, $color = '#FFF', $opacity = 0)
    {
        $image_handler = $this->createGD($width, $height, $color, $opacity);
        $this->setImage($image_handler, $width, $height);
        imagefilledrectangle($image_handler, 0, 0, $width, $height, $this->getColor($color, $opacity));

        return $this;
    }

    /**
     * @param string $format
     * @param integer $quality
     *
     * @throws \LogicException
     */
    public function render($format = 'image/jpeg', $quality = 80)
    {
        switch ($format) {
            case 'image/jpeg':
                header('Content-Type: image/jpeg');
                $background = $this->getJPGBackground();
                imagejpeg($background, null, $quality);
                imagedestroy($background);
                break;
            case 'image/png':
                header('Content-Type: image/png');
                imagesavealpha($this->image_handler, true);
                imagepng($this->image_handler);
                break;
            case 'image/gif':
                header('Content-Type: image/gif');
                imagegif($this->image_handler);
                break;
            default:
                throw new \LogicException("Image type '" . $format . "' don`t support");
        }
        //exit();
    }

    /**
     * @param string $image_path
     * @param string $format
     * @param integer $quality
     *
     * @return $this
     *
     * @throws \LogicException
     */
    public function save($image_path, $format = null, $quality = 80)
    {
        if (is_null($format)) {
            $format = $this->getExtension($image_path);
        }

        switch ($format) {
            case 'image/jpeg':
                $background = $this->getJPGBackground();
                imagejpeg($background, $image_path, $quality);
                imagedestroy($background);
                break;
            case 'image/png':
                imagesavealpha($this->image_handler, true);
                imagepng($this->image_handler, $image_path);
                break;
            case 'image/gif':
                imagegif($this->image_handler, $image_path);
                break;
            default:
                throw new \LogicException("Image type '" . $format . "' don`t support");
        }
        return $this;
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param integer $x
     * @param integer $y
     *
     * @return $this
     */
    public function crop($width, $height, $x = 0, $y = 0)
    {
        if ($width > ($max_width = $this->width - $x)) {
            $width = $max_width;
        }

        if ($height > ($max_height = $this->height - $y))
            $height = $max_height;

        $cropped = $this->createGD($width, $height);
        imagecopy($cropped, $this->image_handler, 0, 0, $x, $y, $width, $height);
        $this->setImage($cropped, $width, $height);

        return $this;
    }

    /**
     * @param integer $scale
     *
     * @return $this
     */
    public function scale($scale)
    {
        $width = ceil($this->width * $scale);
        $height = ceil($this->height * $scale);

        $resized = $this->createGD($width, $height);
        imagecopyresampled($resized, $this->image_handler, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        $this->setImage($resized, $width, $height);

        return $this;
    }

    /**
     * @param integer $angle
     * @param string $bg_color
     * @param integer $bg_opacity
     *
     * @return $this
     */
    public function rotate($angle, $bg_color = '#FFF', $bg_opacity = 0)
    {
        $rotated = imagerotate($this->image_handler, $angle, $this->getColor($bg_color, $bg_opacity));
        imagealphablending($rotated, false);
        $this->setImage($rotated, imagesx($rotated), imagesy($rotated));

        return $this;
    }

    /**
     * @param bool $flip_x
     * @param bool $flip_y
     *
     * @return $this
     */
    public function flip($flip_x = false, $flip_y = false)
    {
        if (!$flip_x && !$flip_y) {
            return $this;
        }

        $x = $flip_x ? $this->width - 1 : 0;
        $width = ($flip_x ? -1 : 1) * $this->width;

        $y = $flip_y ? $this->height - 1 : 0;
        $height = ($flip_y ? -1 : 1) * $this->height;

        $flipped = $this->createGD($this->width, $this->height);
        imagecopyresampled($flipped, $this->image_handler, 0, 0, $x, $y, $this->width, $this->height, $width, $height);
        $this->setImage($flipped, $this->width, $this->height);

        return $this;
    }

    /**
     * @param GDDriver $layer
     * @param integer $x
     * @param integer $y
     *
     * @return $this
     */
    public function overlay($layer, $x = 0, $y = 0)
    {
        imagealphablending($this->image_handler, true);
        imagecopy($this->image_handler, $layer->getImageHandler(), $x, $y, 0, 0, $layer->width, $layer->height);
        imagealphablending($this->image_handler, false);
        return $this;
    }

    /**
     * @param integer $correction
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function gamma($correction)
    {
        if (false === imagegammacorrect($this->image_handler, 1.0, $correction)) {
            throw new \RuntimeException('Failed to apply gamma correction to the image');
        }
        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function negative()
    {
        if (false === imagefilter($this->image_handler, IMG_FILTER_NEGATE)) {
            throw new \RuntimeException('Failed to negate the image');
        }
        return $this;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function grayscale()
    {
        if (false === imagefilter($this->image_handler, IMG_FILTER_GRAYSCALE)) {
            throw new \RuntimeException('Failed to grayscale the image');
        }
        return $this;
    }

    /**
     * @param string $color
     * @param integer $opacity
     *
     * @return $this
     * @throws \RuntimeException
     */
    public function colorize($color, $opacity = 0)
    {
        $color = $this->normalizeColor($color);
        if (false === imagefilter(
                $this->image_handler,
                IMG_FILTER_COLORIZE,
                $color[0],
                $color[1],
                $color[2],
                $opacity
            )
        ) {
            throw new \RuntimeException('Failed to colorize the image');
        }
        return $this;
    }

    /**
     * @return resource
     */
    public function getImageHandler()
    {
        return $this->image_handler;
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return array
        (
            'width' => $this->width,
            'height' => $this->height,
            'mime' => $this->mime_type,
        );
    }

    /**
     * @param resource $image_handler
     * @param integer $width
     * @param integer $height
     */
    protected function setImage($image_handler, $width, $height)
    {
        if ($this->image_handler) {
            imagedestroy($this->image_handler);
        }
        $this->image_handler = $image_handler;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param integer $width
     * @param integer $height
     *
     * @return resource
     */
    protected function createGD($width, $height)
    {
        $image_handler = imagecreatetruecolor($width, $height);
        imagealphablending($image_handler, false);
        return $image_handler;
    }

    /**
     * @param string $color
     * @param integer $opacity
     *
     * @return integer
     */
    protected function getColor($color, $opacity = 0)
    {
        $color = $this->normalizeColor($color);
        return imagecolorallocatealpha($this->image_handler, $color[0], $color[1], $color[2], 127 * (1 - $opacity));
    }

    /**
     * @return resource
     */
    protected function getJPGBackground()
    {
        $background = $this->createGD($this->width, $this->height);
        imagefilledrectangle($background, 0, 0, $this->width, $this->height, $this->getColor('#FFF', 1));
        imagealphablending($background, true);
        imagecopy($background, $this->image_handler, 0, 0, 0, 0, $this->width, $this->height);
        imagealphablending($background, false);

        return $background;
    }

    protected function destroy()
    {
        imagedestroy($this->image_handler);
        unset($this->image_handler);
    }
}
