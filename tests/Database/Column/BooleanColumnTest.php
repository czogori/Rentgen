<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\BooleanColumn;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class BooleanColumnTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     */ 
    public function testCreateInstanceWithoutParams()
    {     	
     	$column = new BooleanColumn();    
    }
    
    public function testCreateInstance()
    {       
        $column = new BooleanColumn('foo');

        $this->assertInstanceOf('Rentgen\Database\Column\BooleanColumn', $column);
    }    

    public function testGetName()
    {
        $column = new BooleanColumn('foo');

        $this->assertEquals('foo', $column->getName());        
    }

    public function testGetType()
    {
        $column = new BooleanColumn('foo');

        $this->assertEquals('boolean', $column->getType());        
    }  

    public function testGetDefaultWithoutSetDefaultValue()
    {
        $column = new BooleanColumn('foo');

        $this->assertNull($column->getDefault());        
    }

    public function testGetDefaultWithBooleanFalse()
    {
        $column = new BooleanColumn('foo', array('default' => false));

        $this->assertEquals('false', $column->getDefault());        
    }

    public function testGetDefaultWithBooleanTrue()
    {
        $column = new BooleanColumn('foo', array('default' => true));

        $this->assertEquals('true', $column->getDefault());        
    }

    public function testGetDefaultWithStringFalse()
    {
        $column = new BooleanColumn('foo', array('default' => 'false'));

        $this->assertEquals('false', $column->getDefault());        
    }

    public function testGetDefaultWithStringTrue()
    {
        $column = new BooleanColumn('foo', array('default' => 'true'));

        $this->assertEquals('true', $column->getDefault());        
    }    

    public function testGetDefaultWithIntegerFalse()
    {
        $column = new BooleanColumn('foo', array('default' => 0));

        $this->assertEquals('false', $column->getDefault());        
    }

    public function testGetDefaultWithIntegerTrue()
    {
        $column = new BooleanColumn('foo', array('default' => 1));

        $this->assertEquals('true', $column->getDefault());        
    }    
}
