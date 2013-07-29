<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Postgres\Manipulation\CreateTableCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Connection;
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
        $sql = 'CREATE TABLE foo(foo_id serial NOT NULL,CONSTRAINT foo_pkey PRIMARY KEY (foo_id));';
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
}
