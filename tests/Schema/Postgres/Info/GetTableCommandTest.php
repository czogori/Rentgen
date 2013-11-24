<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Adapter\Postgres\Info\GetTableCommand;
use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateTableCommand;
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

        $columns = array(new StringColumn('name', array('not_null' => true, 'default' => 'foo', 'limit' => 150)));
        $this->createTable('foo', $columns);
    }

    public function testExecute()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $tableInfo = $getTableCommand->execute();

        $this->assertCount(2, $tableInfo->getColumns());
        $this->assertEquals('foo_id', $tableInfo->getColumn('foo_id')->getName());
        $this->assertEquals('integer', $tableInfo->getColumn('foo_id')->getType());

        $this->assertEquals('name', $tableInfo->getColumn('name')->getName());
        $this->assertEquals('string', $tableInfo->getColumn('name')->getType());
        $this->assertTrue($tableInfo->getColumn('name')->isNotNull());
        $this->assertEquals('foo', $tableInfo->getColumn('name')->getDefault());
        $this->assertEquals(150, $tableInfo->getColumn('name')->getLimit());
    }

    public function testConstraints()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $tableInfo = $getTableCommand->execute();

        $this->assertCount(1, $tableInfo->getConstraints());

        $constraints = $tableInfo->getConstraints();
        $this->assertInstanceOf('Rentgen\Database\Constraint\PrimaryKey', $constraints[0]);
        $this->assertEquals('foo_pkey', $constraints[0]->getName());
        $this->assertEquals('foo_id', $constraints[0]->getColumns());
    }
}
