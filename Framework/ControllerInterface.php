<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

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
	function setContainer(Container $container = NULL);
}