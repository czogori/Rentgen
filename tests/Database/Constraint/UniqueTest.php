<?php

namespace Rentgen\Tests\Database\Constraint;

use Rentgen\Database\Table;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Column\StringColumn;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class UniqueTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
    	$this->unique = new Unique(array('foo', 'bar'), new Table('test'));		
	}
    
    public function testGetName()
    {
    	$this->assertSame('test_foo_bar_key', $this->unique->getName());	
    }

    public function testGetColumns()
    {
    	$this->assertSame(array('foo', 'bar'), $this->unique->getColumns());
    }

    public function testGetTable()
    {
    	$this->assertEquals(new Table('test'), $this->unique->getTable());
    }
}
