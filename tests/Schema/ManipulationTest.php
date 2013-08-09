<?php

namespace Rentgen\Tests\Schema;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ManipulationTest extends TestHelpers
{
	private $manipulation;

    public function setUp()
    {
        $this->clearDatabase();
        
     	$this->manipulation = new Manipulation(new Rentgen());
    }

    public function testCreateTable()
    {     	
     	$this->manipulation->createTable(new Table('foo'));
        $this->assertTrue($this->tableExists('foo'));
    }

    public function testDropTable()
    {
     	$this->manipulation->createTable(new Table('foo'));
        $this->assertTrue($this->tableExists('foo'));

        $this->manipulation->dropTable(new Table('foo'));
        $this->assertFalse($this->tableExists('foo'));
    }
}
