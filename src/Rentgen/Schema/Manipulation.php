<?php

namespace Rentgen\Schema;

use Rentgen\Rentgen;
use Rentgen\Database\Table;

class Manipulation
{
	private $rentgen;

	public function __construct(Rentgen $rentgen)
	{
		$this->rentgen = $rentgen;
	}

	public function createTable(Table $table)
	{
		return $this->rentgen
			->get('create_table')
			->setTable($table)
    		->execute();
	}	

	public function dropTable(Table $table)
	{
		return $this->rentgen
			->get('drop_table')
			->setTable($table)
    		->execute();
	}
}