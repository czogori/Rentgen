<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Postgres\Info\GetTablesCommand;
use Rentgen\Schema\Postgres\Manipulation\DropAllTablesCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Connection;

use Rentgen\Tests\TestHelpers;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class GetTablesCommandTest extends TestHelpers
{
    public function setUp()
    {
    	$this->clearDatabase();
    }

    public function testGetSql()
    {            	
        $getTablesCommand = new GetTablesCommand();        
        $expect = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';";
        $this->assertEquals($expect, $getTablesCommand->getSql());
    }

    public function testExecute()
    {
        $this->createTable('foo');
        $this->createTable('bar');

        $getTablesCommand = new GetTablesCommand();
        $getTablesCommand->setConnection($this->connection);
        $tables = $getTablesCommand->execute();
        
        $this->assertCount(2, $tables);
        $this->assertTrue($this->tableExists('foo'));
        $this->assertTrue($this->tableExists('bar'));        
    }

}