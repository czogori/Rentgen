<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateTableCommand;
use Rentgen\Schema\Adapter\Postgres\Info\GetTableCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Database\Connection;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class CreateTableCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $table = new Table('foo');
        $createTableCommand = new CreateTableCommand();
        $createTableCommand->setTable($table);
        $sql = 'CREATE TABLE public.foo(foo_id serial NOT NULL,CONSTRAINT foo_pkey PRIMARY KEY (foo_id));';
        $this->assertEquals($sql, $createTableCommand->getSql());
    }

    public function testExecute()
    {
        $table = new Table('foo');
        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable($table)
            ->execute();

        $this->assertTrue($this->tableExists('foo'));
    }

    public function testCreateTableWithMultiPrimaryKey()
    {
        $table = new Table('test');
        $table->addColumn(new StringColumn('foo'));
        $table->addColumn(new StringColumn('bar'));
        $table->addConstraint(new PrimaryKey(array('foo', 'bar')));
        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable($table)            
            ->execute();

        $this->assertTrue($this->tableExists('test'));
    }

    public function testCreateTableWithNotAutoIncrementPrimaryKey()
    {
        $primaryKey = new PrimaryKey();
        $primaryKey->disableAutoIncrement();
        $table = new Table('foo');
        $table->addConstraint($primaryKey);
        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable($table)            
            ->execute();

        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $tableInfo = $getTableCommand->execute();

        $this->assertEquals('integer', $tableInfo->getColumn('foo_id')->getType());
    }
}
