<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateSchemaCommand;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class CreateSchemaCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $schemaName = 'foo';
        $createSchemaCommand = new CreateSchemaCommand();
        $createSchemaCommand->setName($schemaName);
        $sql = 'CREATE SCHEMA foo;';
        $this->assertEquals($sql, $createSchemaCommand->getSql());
    }

    public function testExecute()
    {
        $schemaName = 'foo' . time();
        $createSchemaCommand = new CreateSchemaCommand();
        $createSchemaCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setName($schemaName)
            ->execute();

        $this->assertTrue($this->schemaExists($schemaName));
    }
}
