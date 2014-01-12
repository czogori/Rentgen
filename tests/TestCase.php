<?php

namespace Rentgen\Tests;

use Rentgen\Schema\Adapter\Postgres\Info\GetTablesCommand;
use Rentgen\Schema\Adapter\Postgres\Info\TableExistsCommand;
use Rentgen\Schema\Adapter\Postgres\Info\SchemaExistsCommand;
use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateTableCommand;
use Rentgen\Schema\Adapter\Postgres\Manipulation\DropAllTablesCommand;
use Rentgen\Database\Connection\Connection;
use Rentgen\Database\Connection\ConnectionConfig;
use Rentgen\Database\Table;
use Rentgen\Database\Column;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $connection;

    protected function setConnection()
    {
        $fileLocator = new FileLocator(getcwd());
        $configFile = $fileLocator->locate('rentgen.yml');
        $config = Yaml::parse($configFile);

        $this->connection = new Connection(new ConnectionConfig($config['connection']));
    }

    protected function clearDatabase()
    {
        $this->setConnection();

        $sql = 'select schema_name
                from information_schema.schemata
                where schema_name <> \'information_schema\' and schema_name !~ \'^pg_\'';
        $items = $this->connection->query($sql);
        $schemas = array();
        foreach ($items as $item) {
            $this->connection->execute(sprintf('DROP SCHEMA "%s" CASCADE', $item['schema_name']));
        }
        $this->connection->execute(sprintf('CREATE SCHEMA public'));
    }

    protected function createSchema($schemaName)
    {
        $sql = sprintf('CREATE SCHEMA "%s"', $schemaName);
        $this->connection->execute($sql);
    }

    protected function dropSchema($schemaName)
    {
        $sql = sprintf('DROP SCHEMA %s CASCADE', $schemaName);
        $this->connection->execute($sql);
    }

    protected function createTable($name, $columns = array(), $constraints = array())
    {
        $table = new Table($name);
        foreach ($columns as $column) {
            $table->addColumn($column);
        }
        foreach ($constraints as $constraint) {
            $table->addConstraint($constraint);
        }
        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable($table)
            ->execute();
    }

    protected function getTable($name)
    {
        $getTableCommand = new GetTableCommand();

        return $getTableCommand
            ->setConnection($this->connection)
            ->setTableName($name)
            ->execute();
    }

    protected function getTables($schemaName)
    {
        $getTablesCommand = new GetTablesCommand();
        $getTablesCommand
            ->setConnection($this->connection)
            ->setSchemaName($schemaName);
        return $getTablesCommand->execute();
    }

    protected function tableExists($name)
    {
        $tableExists = new TableExistsCommand();

        return $tableExists
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable(new Table($name))
            ->execute();
    }

    protected function schemaExists($name)
    {
        $schemaExists = new SchemaExistsCommand();

        return $schemaExists
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setName($name)
            ->execute();
    }
}
