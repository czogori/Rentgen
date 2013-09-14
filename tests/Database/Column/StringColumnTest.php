<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\StringColumn;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class StringColumnTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     */ 
    public function testCreateInstanceWithoutParams()
    {     	
     	$column = new StringColumn();    
    }
    
    public function testCreateInstance()
    {       
        $column = new StringColumn('foo');

        $this->assertInstanceOf('Rentgen\Database\Column', $column);
        $this->assertInstanceOf('Rentgen\Database\Column\LimitableInterface', $column);
        $this->assertInstanceOf('Rentgen\Database\Column\StringColumn', $column);
    }    

    public function testGetName()
    {
        $column = new StringColumn('foo');

        $this->assertEquals('foo', $column->getName());        
    }

    public function testGetType()
    {
        $column = new StringColumn('foo');

        $this->assertEquals('string', $column->getType());        
    }   

    public function testColumnGetLimit()
    {
        $column = new StringColumn('foo', array('limit' => 50));

        $this->assertEquals(50, $column->getLimit());        
    }  

    public function testColumnWithoutLimit()
    {
        $column = new StringColumn('foo');

        $this->assertNull($column->getLimit());        
    }  

    public function testGetDefaultWithoutSpecifiedInOptions()
    {
        $column = new StringColumn('foo');        

        $this->assertNull($column->getDefault());
    }

    public function testGetDefault()
    {
        $column = new StringColumn('foo', array('default' => 'bar'));

        $this->assertEquals('bar', $column->getDefault());        
    }
}
