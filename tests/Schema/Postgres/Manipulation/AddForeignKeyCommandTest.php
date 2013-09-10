<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Postgres\Manipulation\AddForeignKeyCommand;
use Rentgen\Database\Table;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\ForeignKey;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class AddForeignKeyCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        // $createTableCommand = new DropTableCommand();
        // $createTableCommand->setTable(new Table('foo'));
        // $this->assertEquals('DROP TABLE foo;', $createTableCommand->getSql());
    }

    public function testExecute()
    {
        $table = new Table('foo');
        $referencedTable = new Table('bar');

        $this->createTable('foo', array(new IntegerColumn('bar_id')));
        $this->createTable('bar');

        $foreignKey = new ForeignKey($table, $referencedTable);
        $foreignKey
            ->setColumns(array('bar_id'))
            ->setReferencedColumns(array('bar_id'))
            ->onUpdateCascade()
            ->onDeleteCascade();

        $addForeignKeyCommand = new AddForeignKeyCommand();
        $addForeignKeyCommand
            ->setConnection($this->connection)
            ->setForeignKey($foreignKey)
            ->setTable($table)
            ->execute();
        //echo $addForeignKeyCommand->getSql();
        //$addForeignKeyCommand->execute();
    }
}
