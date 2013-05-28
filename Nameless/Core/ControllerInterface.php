<?php

/**
 * This file is part of the Nameless framework.
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2013. Corpsee <poisoncorpsee@gmail.com>
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
	 * @param \Pimple $container
	 */
	function setContainer(\Pimple $container);
}