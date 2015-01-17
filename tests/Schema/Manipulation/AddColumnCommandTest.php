<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Manipulation\AddColumnCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\CustomColumn;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class AddColumnCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $column = new StringColumn('name');
        $column->setTable(new Table('foo'));

        $addColumnCommand = new AddColumnCommand();
        $addColumnCommand->setColumn($column);

        $sql = 'ALTER TABLE public.foo ADD COLUMN name character varying;';
        $this->assertEquals($sql, $addColumnCommand->getSql());
    }

    public function testAddCustomColumnWithHstoreType()
    {
        $this->connection->execute('CREATE EXTENSION IF NOT EXISTS hstore;');
        $column = new CustomColumn('name', 'hstore');
        $column->setTable(new Table('foo'));

        $addColumnCommand = new AddColumnCommand();
        $addColumnCommand->setColumn($column);

        $sql = 'ALTER TABLE public.foo ADD COLUMN name hstore;';
        $this->assertEquals($sql, $addColumnCommand->getSql());
    }
}
