<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Info\GetSchemasCommand;
use Rentgen\Database\Connection;

use Rentgen\Tests\TestCase;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class GetSchemasCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $getSchemasCommand = new GetSchemasCommand();
        $expect = 'SELECT schema_name
                FROM information_schema.schemata
                WHERE schema_name <> \'information_schema\'
                AND schema_name !~ \'^pg_\'';
        $this->assertEquals($expect, $getSchemasCommand->getSql());
    }

    public function testExecute()
    {
        $this->connection->execute('CREATE SCHEMA foo');
        $this->connection->execute('CREATE SCHEMA bar');

        $getSchemasCommand = new GetSchemasCommand();
        $schemas = $getSchemasCommand
            ->setConnection($this->connection)
            ->execute();

        $this->assertCount(3, $schemas);

        foreach ($schemas as $schema) {
            $this->assertTrue(in_array($schema->getName(), array('public', 'foo', 'bar')));
        }
    }
}
