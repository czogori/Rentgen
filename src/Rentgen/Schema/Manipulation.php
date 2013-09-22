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

	private $createTableCommand;
	private $dropTableCommand;
	private $addForeignKey;
	private $dropForeignKey;
	private $connection;

	/**
	 * Constructor.
	 * 
	 * @param Symfony\Component\DependencyInjection\ContainerInterface $container 
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Create a table.
	 * 
	 * @param  Table  $table       Table instance.
	 * @param  array  $constraints Array of constraints
	 * 
	 * @return integer
	 */
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
	
	/**
	 * Drop a table.
	 * 
	 * @param  Table   $table   Table instance
	 * @param  boolean $cascade If drop cascade
	 * @return integer
	 */
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

	/**
	 * Add a foreign key.
	 * 
	 * @param ForeignKey $foreignKey ForeginKey instance.
	 *
	 * @return integer
	 */
	public function addForeignKey(ForeignKey $foreignKey)
	{
		return $this->container
			->get('add_foreign_key')			
			->setForeignKey($foreignKey)
    		->execute();
	}

	/**
	 * Drop a foregin key.
	 * 
	 * @param  ForeignKey $foreignKey ForeignKey instance.
	 * 
	 * @return integer
	 */
	public function dropForeignKey(ForeignKey $foreignKey)
	{
		return $this->container
			->get('drop_constraint')			
			->setConstraint($foreignKey)
    		->execute();
	}

	/**
	 * Execute SQL query.
	 * 
	 * @param  string  $sql Sql query.
	 * 
	 * @return integer
	 */
	public function execute($sql)
	{
		return $this->container
			->get('connection')
			->execute($sql);
	}
}