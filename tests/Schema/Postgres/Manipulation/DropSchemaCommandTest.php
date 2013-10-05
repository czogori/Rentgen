<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateSchemaCommand;
use Rentgen\Schema\Adapter\Postgres\Manipulation\DropSchemaCommand;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class DropSchemaCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $schemaName = 'foo';
        $createSchemaCommand = new DropSchemaCommand();
        $createSchemaCommand->setName($schemaName);
        $sql = 'DROP SCHEMA foo;';
        $this->assertEquals($sql, $createSchemaCommand->getSql());
    }

    public function testExecute()
    {
        $schemaName = 'foo_drop_test' . time();
        $createSchemaCommand = new CreateSchemaCommand();
        $createSchemaCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setName($schemaName)
            ->execute();

        $this->assertTrue($this->schemaExists($schemaName));

        $dropSchemaCommand = new DropSchemaCommand();
        $dropSchemaCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setName($schemaName)
            ->execute();

         $this->assertFalse($this->schemaExists($schemaName));
    }
}
