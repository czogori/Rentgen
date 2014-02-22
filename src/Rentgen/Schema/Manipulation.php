<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Rentgen;
use Rentgen\Database\Column;
use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Database\DatabaseObjectInterface;
use Rentgen\Database\Index;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;

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
     * Clear current database. Turn database to default state - empty public schema.
     *
     * @return integer
     */
    public function clearDatabase()
    {
        $clearDatabaseCommand = $this->container->get('rentgen.clear_database');

        return $clearDatabaseCommand->execute();
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
        if ($databaseObject instanceof Column) {
           $command = $this->container
                ->get($isCreate ? 'rentgen.add_column' : 'rentgen.drop_column')
                ->setColumn($databaseObject);
        } elseif ($databaseObject instanceof ConstraintInterface) {
            $command = $this->container
                ->get($isCreate ? 'rentgen.add_constraint' : 'rentgen.drop_constraint')
                ->setConstraint($databaseObject);
        } elseif ($databaseObject instanceof Index) {
            $command = $this->container
                ->get($isCreate ? 'rentgen.create_index' : 'rentgen.drop_index')
                ->setIndex($databaseObject);
        } elseif ($databaseObject instanceof Schema) {
           $command = $this->container
                ->get($isCreate ? 'rentgen.create_schema' : 'rentgen.drop_schema')
                ->setSchema($databaseObject);
        } elseif ($databaseObject instanceof Table) {
            $command = $this->container
                ->get($isCreate ? 'rentgen.create_table' : 'rentgen.drop_table')
                ->setTable($databaseObject);
        } else {
            throw new \Exception(sprintf("Class %s is not supported", get_class($databaseObject)));
        }

        return $command;
    }
}
