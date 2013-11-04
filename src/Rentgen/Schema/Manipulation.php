<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\Column;
use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Database\Index;

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
     * Create a table.
     *
     * @param Table $table       Table instance.
     * @param array $constraints Array of constraints
     *
     * @return integer
     */
    public function createTable(Table $table, array $constraints = array())
    {
        foreach ($constraints as $constraint) {
            $table->addConstraint($constraint);
        }
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
     * @param  Column   $table   A Column instance     
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
     * @param  Index  $indx Index instance.
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
     * @param  Index  $index Index instance.
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
     * @param  string  $schemaName Schema name.
     * @return integer
     */
    public function createSchema($schemaName)
    {
        $command = $this->container
            ->get('create_schema')
            ->setName($schemaName);

        return $command->execute();
    }

     /**
     * Drop a schema.
     *
     * @param  string  $schemaName Schema name.
     * @return integer
     */
    public function dropSchema($schemaName)
    {
        $command = $this->container
            ->get('drop_schema')
            ->setName($schemaName);

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
