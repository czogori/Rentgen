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
     	$column = new Column();    
    }

    /**
     * @expectedException Exception
     */ 
    public function testCreateInstanceWithOnlyNameParam()
    {       
        $column = new Column('foo');    
    }

    public function testCreateInstance()
    {       
        $column = new Column('foo', 'string');

        $this->assertInstanceOf('Rentgen\Database\Column', $column);
    }    

    public function testGetName()
    {
        $column = new Column('foo', 'string');

        $this->assertEquals('foo', $column->getName());        
    }

    public function testGetType()
    {
        $column = new Column('foo', 'string');

        $this->assertEquals('string', $column->getType());        
    }

    public function testIsNotNull()
    {
        $column = new Column('foo', 'string', array('not_null' => true));

        $this->assertTrue($column->isNotNull());        
    }

    public function testGetDefault()
    {
        $column = new Column('foo', 'string', array('default' => 'bar'));

        $this->assertEquals('bar', $column->getDefault());        
    }

    public function testGetDefaultWithDefaultValue()
    {
        $column = new Column('foo', 'string');

        $this->assertNull($column->getDefault());        
    }

    public function testGetDefaultWithBooleanFalse()
    {
        $column = new Column('foo', 'boolean', array('default' => false));

        $this->assertEquals('false', $column->getDefault());        
    }

    public function testGetDefaultWithBooleanTrue()
    {
        $column = new Column('foo', 'boolean', array('default' => true));

        $this->assertEquals('true', $column->getDefault());        
    }    
}
