<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\PrimaryKey;

class Manipulation
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function createTable(Table $table, array $constraints = array())
	{		
		$command = $this->container
			->get('create_table')
			->setTable($table);    		
    	foreach ($constraints as $constraint) {
    		if($constraint instanceof PrimaryKey) {
    			$command->setPrimaryKey($constraint);
    		}
    	}
    	return $command->execute();
	}	
	
	public function dropTable(Table $table, $cascade = false)
	{
		$dropTableCommand = $this->container
			->get('drop_table')
			->setTable($table);
		if($cascade) {
			$dropTableCommand->cascade();
		}
   		return $dropTableCommand->execute();
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