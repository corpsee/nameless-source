<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Imager;

class Image
{
    protected $driver;

    public function __construct(ImageDriver $driver)
    {
        $this->driver = $driver;
    }

    public function open($file_path)
    {
        return $this->driver->open($file_path);
    }

    public function create($width, $height, $color = '#FFF', $opacity = 0)
    {
        return $this->driver->create($width, $height, $color, $opacity);
    }
}
