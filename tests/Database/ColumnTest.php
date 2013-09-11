<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */ 
    public function testCreateInstanceWithoutParams()
    {     	
     	$column = new FooColumn();    
    }

    public function testGetName()
    {
        $column = new FooColumn('foo');

        $this->assertEquals('foo', $column->getName());        
    }

    public function testGetType()
    {
        $column = new FooColumn('foo');

        $this->assertEquals('foo', $column->getType());        
    }  

    public function testGetDefault()
    {
        $column = new FooColumn('foo');

        $this->assertNull($column->getDefault());
    }

    public function testIsNotNull()
    {
        $column = new FooColumn('foo');

        $this->assertFalse($column->isNotNull());   
    }
}

class FooColumn extends Column
{
    public function getType()
    {
        return 'foo';
    } 
}