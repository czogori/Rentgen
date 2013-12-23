<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\Column;
use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Database\DatabaseObjectInterface;
use Rentgen\Database\Index;
use Rentgen\Helper\ObjectHelper;

class Manipulation
{
    private $container;

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
     * Create a new database object.
     * 
     * @param DatabaseObjectInterface $databaseObject Database object.
     * 
     * @return integer
     */
    public function create(DatabaseObjectInterface $databaseObject)
    {
        $command = $this->getCommand($databaseObject);

        return $command->execute();
    }

    /**
     * Drop a database object.
     * 
     * @param DatabaseObjectInterface $databaseObject Database object.
     * @param bool                    $cascade        Drop databse object cascade.
     * 
     * @return integer
     */
    public function drop(DatabaseObjectInterface $databaseObject, $cascade = false)
    {
        $command = $this->getCommand($databaseObject, false);
        if ($cascade) {
            $command->cascade();
        }

        return $command->execute();
    }

    /**
     * Get a command to execute.
     * 
     * @param DatabaseObjectInterface $databaseObject Database object.
     * @param bool                    $cascade        Drop databse object cascade.
     *
     * @return Rentgen\Schema\Command
     */
    private function getCommand(DatabaseObjectInterface $databaseObject, $isCreate = true)
    {
        if($databaseObject instanceof Column) {
           $command = $this->container
                ->get($isCreate ? 'add_column' : 'drop_column')
                ->setColumn($databaseObject);     
        } elseif ($databaseObject instanceof ConstraintInterface) {
            $command = $this->container
                ->get($isCreate ? 'add_constraint' : 'drop_constraint')
                ->setConstraint($databaseObject);            
        } elseif ($databaseObject instanceof Index) {
            $command = $this->container
                ->get($isCreate ? 'create_index' : 'drop_index')
                ->setIndex($databaseObject);    
        } elseif ($databaseObject instanceof Schema) {
           $command = $this->container
                ->get($isCreate ? 'create_schema' : 'drop_schema')
                ->setSchema($databaseObject);     
        } elseif ($databaseObject instanceof Table) {
            $command = $this->container
                ->get($isCreate ? 'create_table' : 'drop_table')
                ->setTable($databaseObject);
        } else {        
            throw new \Exception(sprintf("Object %s is not supported", $className));                
        }
     
        return $command;
    }

    /**
     * Execute SQL query.
     *
     * @param string $sql Sql query.
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
