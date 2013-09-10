<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Postgres\Manipulation\AddColumnCommand;
use Rentgen\Database\Table;
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
        $addColumnCommand = new AddColumnCommand();
        $addColumnCommand->setTable(new Table('foo'));
        $addColumnCommand->setColumn(new StringColumn('name'));

        $sql = 'ALTER TABLE foo ADD COLUMN name string;';
        $this->assertEquals($sql, $addColumnCommand->getSql());
    }

    public function testExecute()
    {        
    }    
}
