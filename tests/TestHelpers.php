<?php

namespace Rentgen\Tests;

use Rentgen\Schema\Postgres\Info\TableExistsCommand;
use Rentgen\Schema\Postgres\Manipulation\CreateTableCommand;
use Rentgen\Schema\Postgres\Manipulation\DropAllTablesCommand;
use Rentgen\Database\Connection;
use Rentgen\Database\ConnectionConfig;
use Rentgen\Database\Table;
use Rentgen\Database\Column;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TestHelpers extends \PHPUnit_Framework_TestCase
{
    protected $connection;

    protected function setConnection()
    {
        $connectionConfig = $this->getMock('Rentgen\Database\ConnectionConfig');
        $connectionConfig->expects($this->once())->method('getDsn')->will($this->returnValue($GLOBALS['DB_DSN']));
        $connectionConfig->expects($this->once())->method('getLogin')->will($this->returnValue($GLOBALS['DB_USER']));
        $connectionConfig->expects($this->once())->method('getPassword')->will($this->returnValue($GLOBALS['DB_PASSWORD']));
        $this->connection = new Connection($connectionConfig);
    }

    protected function clearDatabase()
    {
        $this->setConnection();

        $dropAllTablesCommand = new DropAllTablesCommand();
        $dropAllTablesCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->execute();
    }

    protected function createTable($name, $columns = array())
    {
        $table = new Table($name);
        foreach ($columns as $columnName => $columnType) {
            $table->addColumn(new Column($columnName, $columnType));
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

    protected function tableExists($name)
    {
        $tableExists = new TableExistsCommand();

        return $tableExists
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable(new Table($name))
            ->execute();
    }
}
