<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

interface ModuleProviderInterface
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
