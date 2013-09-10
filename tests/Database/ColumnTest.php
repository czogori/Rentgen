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

    
}

class FooColumn extends Column
{
    public function getType()
    {
        return 'foo';
    } 
}