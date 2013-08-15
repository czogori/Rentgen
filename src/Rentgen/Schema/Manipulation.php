<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\ForeignKey;

class Manipulation
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function createTable(Table $table)
	{		
		return $this->container
			->get('create_table')
			->setTable($table)
    		->execute();
	}	

	public function dropTable(Table $table)
	{
		return $this->container
			->get('drop_table')
			->setTable($table)
    		->execute();
	}

	public function addForeignKey(ForeignKey $foreignKey)
	{
		return $this->container
			->get('add_foreign_key')			
			->setForeignKey($foreignKey)
    		->execute();
	}

	public function dropForeignKey(ForeignKey $foreignKey)
	{
		return $this->container
			->get('drop_constraint')			
			->setConstraint($foreignKey)
    		->execute();
	}
}