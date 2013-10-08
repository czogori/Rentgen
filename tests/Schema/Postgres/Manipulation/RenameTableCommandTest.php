<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\RenameTableCommand;
use Rentgen\Database\Table;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class RenameTableCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $renameTableCommand = new RenameTableCommand();
        $renameTableCommand->setTable(new Table('foo'));
        $renameTableCommand->setNewName('bar');
        $this->assertEquals('ALTER TABLE public.foo RENAME TO bar;', $renameTableCommand->getSql());
    }

    public function testExecute()
    {
        $tableName = 'foo';
        $this->createTable($tableName);

        $this->assertTrue($this->tableExists($tableName));

        $renameTableCommand = new RenameTableCommand();
        $renameTableCommand
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable(new Table($tableName))
            ->setNewName('bar')
            ->execute();

        $this->assertTrue($this->tableExists('bar'));
    }
}
