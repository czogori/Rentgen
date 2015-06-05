<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\DecimalColumn;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class DecimalColumnTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->column = new DecimalColumn('foo');
    }

    public function testCreateInstance()
    {
        $this->assertInstanceOf('Rentgen\Database\Column', $this->column);
        $this->assertInstanceOf('Rentgen\Database\Column\DecimalColumn', $this->column);
    }

    public function testGetName()
    {
        $this->assertEquals('foo', $this->column->getName());
    }

    public function testGetType()
    {
        $this->assertEquals('decimal', $this->column->getType());
    }

    public function testGetPrecision()
    {
        $column = new DecimalColumn('foo', array('precision' => 10));

        $this->assertEquals(10, $column->getPrecision());
    }

    public function testGetScale()
    {
        $column = new DecimalColumn('foo', array('precision' => 10, 'scale' => 2));

        $this->assertEquals(2, $column->getScale());
    }

    public function testDefaultPresionShouldBeUnknown()
    {
        $column = new DecimalColumn('foo');

        $this->assertNull($column->getPrecision());
    }

    public function testDefaultScaleShouldBeZero()
    {
        $column = new DecimalColumn('foo', array('precision' => 10));

        $this->assertEquals(0, $column->getScale());
    }
}
