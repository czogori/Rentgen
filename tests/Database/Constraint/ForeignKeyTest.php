<?php

namespace Rentgen\Tests\Database\Constraint;

use Rentgen\Database\Table;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\Constraint\ForeignKey;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ForeignTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $table = new Table('foo');
        $table->addColumn(new IntegerColumn('bar_id'));
        $tableReferenced = new Table('bar');

        $this->foreignKey = new ForeignKey(new Table('foo'), new Table('bar'));
        $this->foreignKey->setColumns('bar_id');
        $this->foreignKey->setReferencedColumns('bar_id');
    }

    public function testGetName()
    {
        $this->assertEquals('foo_bar_id_fkey', $this->foreignKey->getName());
    }

    public function testGetTable()
    {
        $this->assertEquals(new Table('foo'), $this->foreignKey->getTable());
    }

    public function testGetReferencedTable()
    {
        $this->assertEquals(new Table('bar'), $this->foreignKey->getReferencedTable());
    }

    public function testGetReferencedColumns()
    {
        $this->assertEquals(array('bar_id'), $this->foreignKey->getReferencedColumns());
    }

}
