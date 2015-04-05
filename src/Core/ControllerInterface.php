<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
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
