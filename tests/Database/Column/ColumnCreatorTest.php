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
         $this->assertInstanceOf('Rentgen\Database\Column\TextColumn', $columnCreator->create('foo', 'text'));
         $this->assertInstanceOf('Rentgen\Database\Column\IntegerColumn', $columnCreator->create('foo', 'integer'));
         $this->assertInstanceOf('Rentgen\Database\Column\BigIntegerColumn', $columnCreator->create('foo', 'biginteger'));
         $this->assertInstanceOf('Rentgen\Database\Column\SmallIntegerColumn', $columnCreator->create('foo', 'smallinteger'));
         $this->assertInstanceOf('Rentgen\Database\Column\FloatColumn', $columnCreator->create('foo', 'float'));
         $this->assertInstanceOf('Rentgen\Database\Column\DecimalColumn', $columnCreator->create('foo', 'decimal'));
         $this->assertInstanceOf('Rentgen\Database\Column\BooleanColumn', $columnCreator->create('foo', 'boolean'));
         $this->assertInstanceOf('Rentgen\Database\Column\DateColumn', $columnCreator->create('foo', 'date'));
         $this->assertInstanceOf('Rentgen\Database\Column\TimeColumn', $columnCreator->create('foo', 'time'));
         $this->assertInstanceOf('Rentgen\Database\Column\DateTimeColumn', $columnCreator->create('foo', 'datetime'));
         $this->assertInstanceOf('Rentgen\Database\Column\BinaryColumn', $columnCreator->create('foo', 'binary'));
    }
}
