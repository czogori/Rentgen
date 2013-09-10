<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\IntegerColumn;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class IntegerColumnTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     */ 
    public function testCreateInstanceWithoutParams()
    {     	
     	$column = new IntegerColumn();    
    }
    
    public function testCreateInstance()
    {       
        $column = new IntegerColumn('foo');

        $this->assertInstanceOf('Rentgen\Database\Column', $column);
        $this->assertInstanceOf('Rentgen\Database\Column\IntegerColumn', $column);
    }    

    public function testGetName()
    {
        $column = new IntegerColumn('foo');

        $this->assertEquals('foo', $column->getName());        
    }

    public function testGetType()
    {
        $column = new IntegerColumn('foo');

        $this->assertEquals('integer', $column->getType());        
    }      
}
