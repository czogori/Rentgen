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

    public function create(DatabaseObjectInterface $databaseObject)
    {
        $command = $this->getCommand($databaseObject);

        return $command->execute();
    }

    public function drop(DatabaseObjectInterface $databaseObject, $cascade = false)
    {
        $command = $this->getCommand($databaseObject, false);
        if ($cascade) {
            $command->cascade();
        }

        return $command->execute();
    }

    private function getCommand(DatabaseObjectInterface $databaseObject, $isCreate = true)
    {
        $className = ObjectHelper::getClassNameWithoutNamespace($databaseObject);
        switch ($className) {
            case 'Index':
                $command = $this->container
                    ->get($isCreate ? 'create_index' : 'drop_index')
                    ->setIndex($databaseObject);    
                break;
            case 'Schema':
                $command = $this->container
                    ->get($isCreate ? 'create_schema' : 'drop_schema')
                    ->setSchema($databaseObject); 
                break;
            case 'Table':
                $command = $this->container
                    ->get($isCreate ? 'create_table' : 'drop_table')
                    ->setTable($databaseObject);
                break;
            
            case 'BigintegerColumn':
            case 'BinaryColumn':
            case 'BooleanColumn':
            case 'DateColumn':
            case 'DatetimeColumn':
            case 'DecimalColumn':
            case 'FloatColumn':
            case 'IntegerColumn':
            case 'SmallintegerColumn':
            case 'StringColumn':
            case 'TextColumn':
            case 'TimeColumn':
                $command = $this->container
                    ->get($isCreate ? 'add_column' : 'drop_column')
                    ->setColumn($databaseObject);            
                break;
            case 'ForeignKey':
            case 'PrimaryKey':
            case 'Unique':
                $command = $this->container
                    ->get($isCreate ? 'add_constraint' : 'drop_constraint')
                    ->setConstraint($databaseObject);                
                break;
            
            default:
                throw new \Exception(sprintf("Object %s is not supported", $className));                
                break;
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
