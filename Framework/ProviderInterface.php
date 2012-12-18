<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

interface ProviderInterface
{
	/**
	 * @param Container $container
	 */
	public function register (Container $container);

	/**
	 * @param Kernel $kernel
	 */
	public function boot (Kernel $kernel);
}
