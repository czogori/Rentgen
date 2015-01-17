<?php

namespace Rentgen\Tests\Schema\Postgres\Info;

use Rentgen\Schema\Info\GetTableCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;

use Rentgen\Tests\TestCase;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class GetTableCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();

        $columns = array(
            new StringColumn('name', array('not_null' => true, 'default' => 'foo', 'limit' => 150)),
            new IntegerColumn('bar_id'),
        );

        $foreignKey = new ForeignKey(new Table('foo'), new Table('bar'));
        $foreignKey->setColumns('bar_id');
        $foreignKey->setReferencedColumns('bar_id');

        $constraints = array(
            $foreignKey,
            new Unique('bar_id', new Table('foo')),
        );

        $this->createTable('bar');
        $this->createTable('foo', $columns, $constraints);
    }

    public function testExecute()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $table = $getTableCommand->execute();

        $this->assertCount(3, $table->getColumns());
        $this->assertEquals('foo_id', $table->getColumn('foo_id')->getName());
        $this->assertEquals('integer', $table->getColumn('foo_id')->getType());

        $this->assertEquals('name', $table->getColumn('name')->getName());
        $this->assertEquals('string', $table->getColumn('name')->getType());
        $this->assertTrue($table->getColumn('name')->isNotNull());
        $this->assertEquals('foo', $table->getColumn('name')->getDefault());
        $this->assertEquals(150, $table->getColumn('name')->getLimit());
    }

    public function testNumberConstraints()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $table = $getTableCommand->execute();

        $this->assertCount(3, $table->getConstraints());
    }

    public function testPrimaryKey()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $table = $getTableCommand->execute();

        foreach ($table->getConstraints() as $constraint) {
            if ($constraint instanceof PrimaryKey) {
                $this->assertInstanceOf('Rentgen\Database\Constraint\PrimaryKey', $constraint);
                $this->assertEquals('foo_pkey', $constraint->getName());
                $this->assertEquals('foo_id', $constraint->getColumns());
            }
        }
    }

    public function testForeignKey()
    {
        $getTableCommand = new GetTableCommand();
        $getTableCommand->setConnection($this->connection);
        $getTableCommand->setTableName('foo');

        $table = $getTableCommand->execute();

        foreach ($table->getConstraints() as $constraint) {
            if ($constraint instanceof ForeignKey) {
                $this->assertInstanceOf('Rentgen\Database\Constraint\ForeignKey', $constraint);
                $this->assertEquals('foo_bar_fkey', $constraint->getName());
            }
        }
    }
}
