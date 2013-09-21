<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateTableCommand;
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
        $table = new Table('foo', array());
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
        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable($table)
            ->setPrimaryKey(new PrimaryKey(array('foo', 'bar')))
            ->execute();

        $this->assertTrue($this->tableExists('test'));   
    }
}
