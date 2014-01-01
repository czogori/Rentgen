<?php

namespace Rentgen\Tests\Database\Constraint;

use Rentgen\Database\Index;
use Rentgen\Database\Table;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class IndexTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->indexSingleColumn = new Index('foo', new Table('test'));
        $this->indexMultiColumns = new Index(array('foo', 'bar'), new Table('test'));
    }

    public function testGetName()
    {
        $this->assertSame('test_foo_idx', $this->indexSingleColumn->getName());
        $this->assertSame('test_foo_bar_idx', $this->indexMultiColumns->getName());
    }

    public function testGetColumns()
    {
        $this->assertSame(array('foo'), $this->indexSingleColumn->getColumns());
        $this->assertSame(array('foo', 'bar'), $this->indexMultiColumns->getColumns());
    }

    public function testGetTable()
    {
        $this->assertEquals(new Table('test'), $this->indexSingleColumn->getTable());
        $this->assertEquals(new Table('test'), $this->indexMultiColumns->getTable());
    }
}
