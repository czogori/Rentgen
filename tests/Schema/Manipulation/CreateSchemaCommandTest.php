<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Database\Schema;
use Rentgen\Schema\Manipulation\CreateSchemaCommand;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class CreateSchemaCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $schema = new Schema('foo');
        $createSchemaCommand = new CreateSchemaCommand();
        $createSchemaCommand->setSchema($schema);
        $sql = 'CREATE SCHEMA foo;';
        $this->assertEquals($sql, $createSchemaCommand->getSql());
    }

    public function testExecute()
    {
        $schema = new Schema('foo');
        $createSchemaCommand = new CreateSchemaCommand();
        $createSchemaCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setSchema($schema)
            ->execute();

        $this->assertTrue($this->schemaExists($schema->getName()));
    }
}
