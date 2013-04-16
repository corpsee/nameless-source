<?php

/*
 * This file is part of the Nameless framework package.
 * (c) Corpsee <poisoncorpsee@gmail.com>
 */
namespace Nameless\Modules\Database;

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