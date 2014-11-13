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

namespace Nameless\Core;

/**
 * Base controller interface class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
interface ControllerInterface
{
    /**
     * @param Container $container
     */
    function setContainer(Container $container);
}