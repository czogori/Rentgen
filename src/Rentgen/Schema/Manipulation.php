<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Database\Constraint\PrimaryKey;

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
        $command = $this->container
            ->get('create_table')
            ->setTable($table);
        foreach ($constraints as $constraint) {
            if ($constraint instanceof PrimaryKey) {
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
        if ($cascade) {
            $dropTableCommand->cascade();
        }

           return $dropTableCommand->execute();
    }

    /**
     * Add a constraint.
     *
     * @param ContainerInterface $constraint A ContainerInterface instance.
     *
     * @return integer
     */
    public function addConstraint(ContainerInterface $constraint)
    {
        return $this->container
            ->get('add_constraint')
            ->setForeignKey($constraint)
            ->execute();
    }

    /**
     * Drop a foregin key.
     *
     * @param ForeignKey $constraint A ContainerInterface instance.
     *
     * @return integer
     */
    public function dropConstraint(ContainerInterface $constraint)
    {
        return $this->container
            ->get('drop_constraint')
            ->setConstraint($constraint)
            ->execute();
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
