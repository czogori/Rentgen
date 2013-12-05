<?php

namespace Rentgen\Schema;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Rentgen\Database\Table;

class Info
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
     * Get table information.
     *
     * @param Table $table Table instance.
     *
     * @return Table Table instance.
     */
    public function getTable(Table $table)
    {
        return $this->container
            ->get('get_table')
            ->setTableName($table->getName())
            ->execute();
    }

    /**
     * Get tables information.     
     *
     * @return Table[] Array of Table instances.
     */
    public function getTables($schemaName = null)
    {
        $getTablesCommand = $this->container->get('get_tables');            
        if(null !== $schemaName) {
            $getTablesCommand->setSchemaName($schemaName);
        }
        return $getTablesCommand->execute();
    }

    /**
     * If table exists.
     *
     * @param Table $table Table instance.
     *
     * @return Boolean
     */
    public function isTableExists(Table $table)
    {
        return $this->container
            ->get('table_exists')
            ->setTable($table)
            ->execute();
    }
}
