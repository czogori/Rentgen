<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\ColumnCreator;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ColumnCreatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {     	
     	$columnCreator = new ColumnCreator();
     	
     	$this->assertInstanceOf('Rentgen\Database\Column\StringColumn', $columnCreator->create('foo', 'string'));
    }      
}
