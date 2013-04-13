<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

interface ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (\Pimple $container);

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel);
}
