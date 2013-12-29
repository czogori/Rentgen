<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\AddColumnCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\CustomColumn;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class AddColumnCommandTest extends TestHelpers
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

    public function testAddCustomColumn()
    {
        $column = new CustomColumn('name', 'hstore');
        $column->setTable(new Table('foo'));

        $addColumnCommand = new AddColumnCommand();
        $addColumnCommand->setColumn($column);

        $sql = 'ALTER TABLE public.foo ADD COLUMN name hstore;';
        $this->assertEquals($sql, $addColumnCommand->getSql());
    }
}
