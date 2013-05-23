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
 * Benchmark class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Benchmark
{
	protected $start_time;
	protected $markers;

	public function __construct ($start_time, array &$markers)
	{
		$this->start_time = $start_time;
		$this->markers    = $markers;
	}

	public function start ($marker)
	{
		$this->markers[$marker]['start_time']   = microtime(TRUE);
		$this->markers[$marker]['start_memory'] = memory_get_usage();

		$this->markers[$marker]['stop_time']   = NULL;
		$this->markers[$marker]['stop_memory'] = NULL;
	}

	public function stop ($marker)
	{
		$this->markers[$marker]['stop_time']   = microtime(TRUE);
		$this->markers[$marker]['stop_memory'] = memory_get_usage();
	}

	/*public function mark ($mark_name)
	{
		$this->markers[$mark_name] = microtime(TRUE);
	}

	function elapsed_time($actual_mark = NULL, $preview_mark = NULL)
	{
		if ($preview_mark !== NULL && !isset($this->markers[$preview_mark]))
		{
			throw new \InvalidArgumentException('Mark ' . $preview_mark . ' don`t exist!');
		}

		if ($actual_mark !== NULL && !isset($this->markers[$actual_mark]))
		{
			throw new \InvalidArgumentException('Mark ' . $actual_mark . ' don`t exist!');
		}

		$preview_mark  = is_null($preview_mark) ? $this->start_time : $this->markers[$preview_mark];
		$actual_mark   = is_null($actual_mark) ? microtime(TRUE) : $this->markers[$actual_mark];

		return $actual_mark - $preview_mark;
	}*/
}