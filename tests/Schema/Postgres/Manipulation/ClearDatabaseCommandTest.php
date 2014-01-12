<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Database\Schema;
use Rentgen\Schema\Adapter\Postgres\Manipulation\ClearDatabaseCommand;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ClearDatabaseCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();

        $getSchemasCommand = $this->getMock('Rentgen\Schema\Adapter\Postgres\Info\getSchemasCommand');
        $getSchemasCommand->expects($this->any())
             ->method('execute')
             ->will($this->returnValue(array(new Schema('public'), new Schema('foo'))));

        $this->clearDatabaseCommand = new ClearDatabaseCommand($getSchemasCommand);
        $this->clearDatabaseCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'));
    }

    public function testGetSql()
    {
        $expectedSql = 'DROP SCHEMA "public" CASCADE;DROP SCHEMA "foo" CASCADE;CREATE SCHEMA public;';
        $this->assertEquals($expectedSql, $this->clearDatabaseCommand->getSql());
    }

    public function testExecute()
    {
        $this->createSchema('foo');

        $this->assertTrue($this->schemaExists('public'));
        $this->assertTrue($this->schemaExists('foo'));

        $this->clearDatabaseCommand->execute();

        $this->assertTrue($this->schemaExists('public'));
        $this->assertFalse($this->schemaExists('foo'));
    }
}
