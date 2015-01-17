<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Manipulation\DropTableCommand;
use Rentgen\Database\Table;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class DropTableCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $createTableCommand = new DropTableCommand();
        $createTableCommand->setTable(new Table('foo'));
        $this->assertEquals('DROP TABLE public.foo;', $createTableCommand->getSql());
    }

    public function testExecute()
    {
        $tableName = 'foo';
        $this->createTable($tableName);

        $this->assertTrue($this->tableExists($tableName));

        $dropTableCommand = new DropTableCommand();
        $dropTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable(new Table($tableName))
            ->execute();

        $this->assertFalse($this->tableExists($tableName));
    }
}
