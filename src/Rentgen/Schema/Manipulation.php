<?php

namespace Rentgen\Schema;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\ForeignKey;

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

	public function addForeignKey(ForeignKey $foreignKey)
	{
		return $this->rentgen
			->get('add_foreign_key')			
			->setForeignKey($foreignKey)
    		->execute();
	}

	public function dropForeignKey(ForeignKey $foreignKey)
	{
		return $this->rentgen
			->get('drop_constraint')			
			->setConstraint($foreignKey)
    		->execute();
	}
}