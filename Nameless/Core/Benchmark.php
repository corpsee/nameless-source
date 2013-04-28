<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Core;

class Benchmark
{
	protected $start_time;
	protected $markers;

	public function __construct ($start_time, $markers)
	{
		$this->start_time = $start_time;
	}

	public function mark ($mark_name)
	{
		//TODO: писать в контейнер
		$this->markers[$mark_name] = microtime(TRUE);
	}

	function elapsed_time($second_mark = NULL, $first_mark = NULL)
	{
		$start_time = is_null($first_mark) ? $this->start_time : $this->markers[$first_mark];
		$end_time   = is_null($second_mark) ? microtime(TRUE) : $this->markers[$second_mark];

		return $end_time - $start_time;
	}
}