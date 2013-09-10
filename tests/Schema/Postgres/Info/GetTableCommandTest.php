<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Postgres\Info\GetTableCommand;
use Rentgen\Schema\Postgres\Manipulation\CreateTableCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\StringColumn;

use Rentgen\Tests\TestHelpers;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class GetTableCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {

    }

    public function testExecute()
    {
        $table = new Table('foo');
        $table->addColumn(new StringColumn('name'));

        $createTableCommand = new CreateTableCommand();
        $createTableCommand
            ->setTable($table)
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->execute();

        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $tableInfo = $getTableCommand->execute();

        $this->assertCount(2, $tableInfo->getColumns());
        $this->assertEquals('foo_id', $tableInfo->getColumn('foo_id')->getName());
        $this->assertEquals('name', $tableInfo->getColumn('name')->getName());

        $this->assertEquals('integer', $tableInfo->getColumn('foo_id')->getType());
        $this->assertEquals('string', $tableInfo->getColumn('name')->getType());
    }
}
