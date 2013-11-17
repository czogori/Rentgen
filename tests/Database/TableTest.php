<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Column\StringColumn;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Exception
     */
    public function testCreateInstanceWithoutParams()
    {
        $table = new Table();
    }

    public function testCreateInstanceWithoutSchemaParam()
    {
        $table = new Table('foo');
        $this->assertEquals('foo', $table->getName());
        $schema = new Schema('public');
        $expected = $schema->getName();

        $this->assertEquals($expected, $table->getSchema()->getName());        
    }

    public function testCreateInstanceWithAllParams()
    {
        $table = new Table('foo', new Schema('bar'));
        $this->assertEquals('foo', $table->getName());                
        $this->assertThat(new Schema('bar'), 
            $this->logicalAnd($this->equalTo($table->getSchema())
          )
        );
    }    

    public function testGetName()
    {
        $table = new Table('foo');
        $this->assertEquals('foo', $table->getName());
    }

    public function testGetQualifiedName()
    {
        $table = new Table('foo');
        $this->assertEquals('public.foo', $table->getQualifiedName());   
    }

    public function testColumns()
    {
        $table = new Table('foo');
        $table->addColumn(new StringColumn('bar1'));
        $table->addColumn(new StringColumn('bar2'));

        $this->assertCount(2, $table->getColumns());

        $columns = $table->getColumns();
        $this->assertEquals('bar1', $columns[0]->getName());
        $this->assertEquals('bar2', $columns[1]->getName());
    }

    public function testConstraints()
    {
        $table = new Table('foo');        
        $table->addConstraint(new PrimaryKey(array('pk')));
        $table->addConstraint(new ForeignKey($table, new Table('bar')));
        $table->addConstraint(new Unique('uniq'));        

        $this->assertCount(3, $table->getConstraints());

        $constraints = $table->getConstraints();
        $this->assertInstanceOf('Rentgen\Database\Constraint\PrimaryKey', $constraints[0]);
        $this->assertInstanceOf('Rentgen\Database\Constraint\ForeignKey', $constraints[1]);
        $this->assertInstanceOf('Rentgen\Database\Constraint\Unique', $constraints[2]);
    }

    public function testSchema()
    {
        $table = new Table('foo');
        $table->setSchema(new Schema('bar'));
        $this->assertThat(new Schema('bar'), 
            $this->logicalAnd($this->equalTo($table->getSchema())
          )
        );
    }
}
