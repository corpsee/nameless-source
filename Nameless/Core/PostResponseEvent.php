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

use Symfony\Component\HttpKernel\Event\PostResponseEvent as BasePostResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PostResponseEvent class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class PostResponseEvent extends BasePostResponseEvent
{
	/**
	 * @var Benchmark
	 */
	protected $benchmark;

	/**
	 * @param HttpKernelInterface $kernel
	 * @param Request             $request
	 * @param Response            $response
	 * @param Benchmark           $benchmark
	 */
	public function __construct (HttpKernelInterface $kernel, Request $request, Response $response, Benchmark $benchmark)
	{
		parent::__construct($kernel, $request, $response);
		$this->benchmark = $benchmark;
	}

	/**
	 * @return Benchmark
	 */
	public function getBenchmark ()
	{
		return $this->benchmark;
	}
}