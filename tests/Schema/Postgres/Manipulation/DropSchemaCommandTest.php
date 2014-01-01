<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Database\Schema;
use Rentgen\Schema\Adapter\Postgres\Manipulation\CreateSchemaCommand;
use Rentgen\Schema\Adapter\Postgres\Manipulation\DropSchemaCommand;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class DropSchemaCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function tearDown()
    {
        if ($this->schemaExists('foo')) {
            $this->dropSchema('foo');
        }
    }

    public function testGetSql()
    {
        $schema = new Schema('foo');
        $createSchemaCommand = new DropSchemaCommand();
        $createSchemaCommand->setSchema($schema);
        $sql = 'DROP SCHEMA foo;';
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

        $dropSchemaCommand = new DropSchemaCommand();
        $dropSchemaCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setSchema($schema)
            ->execute();

         $this->assertFalse($this->schemaExists($schema->getName()));
    }
}
