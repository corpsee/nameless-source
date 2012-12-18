<?php

/*
 * This file is part of the Framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Framework;

class Model
{
	/**
	 * @var Database
	 */
	protected $database;

	/**
	 * @param Database $database
	 */
	public function __construct(Database $database)
	{
		$this->database = $database;
	}
}