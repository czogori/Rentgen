<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Adapter\Postgres\Info\GetTablesCommand;
use Rentgen\Database\Connection;

use Rentgen\Tests\TestCase;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class GetTablesCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $getTablesCommand = new GetTablesCommand();
        $expect = "SELECT table_name, table_schema FROM information_schema.tables WHERE table_schema <> 'information_schema'
            AND table_schema <> 'pg_catalog';";
        $this->assertEquals($expect, $getTablesCommand->getSql());
    }

    public function testGetSqlWithSetSchema()
    {
        $getTablesCommand = new GetTablesCommand();
        $getTablesCommand->setSchemaName('foo');
        $expect = "SELECT table_name, table_schema FROM information_schema.tables WHERE table_schema <> 'information_schema'
            AND table_schema <> 'pg_catalog' AND table_schema = 'foo';";
        $this->assertEquals($expect, $getTablesCommand->getSql());
    }

    public function testExecute()
    {
        $this->createTable('foo');
        $this->createTable('bar');

        $tables = $this->getTablesCommand();

        $this->assertCount(2, $tables);
        foreach ($tables as $table) {
            $this->assertTrue(in_array($table->getName(), array('foo', 'bar')));
        }
    }

    public function testListTablesInManySchemas()
    {
        $this->createSchema('another_schema');
        $this->createTable('foo');
        $this->createTable('bar', array(), array(), 'another_schema');

        $tables = $this->getTablesCommand();

        $this->assertCount(2, $tables);
        foreach ($tables as $table) {
            $this->assertTrue(in_array($table->getName(), array('foo', 'bar')));
            if ($table->getName() == 'bar') {
                $this->assertEquals('another_schema', $table->getSchema()->getName());
            }
        }
    }

    private function getTablesCommand()
    {
        $getTablesCommand = new GetTablesCommand();
        $getTablesCommand->setConnection($this->connection);

        return $getTablesCommand->execute();
    }
}
