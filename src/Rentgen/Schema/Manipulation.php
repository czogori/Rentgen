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
     * Create a table.
     *
     * @param Table $table Table instance.     
     *
     * @return integer
     */
    public function createTable(Table $table)
    {        
        $command = $this->container
            ->get('create_table')
            ->setTable($table);

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
        if ($cascade) {
            $dropTableCommand->cascade();
        }

        return $dropTableCommand->execute();
    }

    /**
     * Add a column to a table.
     *
     * @param  Column  $table A Column instance
     * @return integer
     */
    public function addColumn(Column $column)
    {
        $command = $this->container
            ->get('add_column')
            ->setColumn($column);

        return $command->execute();
    }

    /**
     * Drop a column from a table.
     *
     * @param  Column  $table A column instance
     * @return integer
     */
    public function dropColumn(Column $column)
    {
        $command = $this->container
            ->get('drop_column')
            ->setColumn($column);

        return $command->execute();
    }

    /**
     * Add a constraint.
     *
     * @param ConstraintInterface $constraint A ConstraintInterface instance.
     *
     * @return integer
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        return $this->container
            ->get('add_constraint')
            ->setConstraint($constraint)
            ->execute();
    }

    /**
     * Drop a foregin key.
     *
     * @param ForeignKey $constraint A ConstraintInterface instance.
     *
     * @return integer
     */
    public function dropConstraint(ConstraintInterface $constraint)
    {
        return $this->container
            ->get('drop_constraint')
            ->setConstraint($constraint)
            ->execute();
    }

     /**
     * Create a index.
     *
     * @param  Index   $indx Index instance.
     * @return integer
     */
    public function createIndex(Index $index)
    {
        $command = $this->container
            ->get('create_index')
            ->setIndex($index);

        return $command->execute();
    }

     /**
     * Drop a index.
     *
     * @param  Index   $index Index instance.
     * @return integer
     */
    public function dropIndex(Index $index)
    {
        $command = $this->container
            ->get('drop_index')
            ->setIndex($index);

        return $command->execute();
    }

    /**
     * Create a schema.
     *
     * @param  Schema  $schema The Schema instance.
     * @return integer
     */
    public function createSchema(Schema $schema)
    {
        $command = $this->container
            ->get('create_schema')
            ->setSchema($schema);

        return $command->execute();
    }

     /**
     * Drop a schema.
     *
     * @param  string  $schema The Schema instance.
     * @return integer
     */
    public function dropSchema(Schema $schema)
    {
        $command = $this->container
            ->get('drop_schema')
            ->setSchema($schema);

        return $command->execute();
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
