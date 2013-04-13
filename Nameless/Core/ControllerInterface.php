<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
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
	function setContainer(\Pimple $container = NULL);
}