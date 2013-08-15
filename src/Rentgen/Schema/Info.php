<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Database\Table;

class Info
{
	private $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function isTableExists(Table $table)
	{		
		return $this->container
			->get('table_exists')
			->setTable($table)
    		->execute();
	}	
}