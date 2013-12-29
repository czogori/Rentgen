<?php

namespace Rentgen\Schema\Postgres\Info;

use Rentgen\Schema\Adapter\Postgres\Info\TableExistsCommand;
use Rentgen\Database\Table;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TableExistsCommandTest extends TestCase
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testGetSql()
    {
        $table = new Table('foo');
        $createTable = new TableExistsCommand();
        $createTable->setTable($table);
        $expect = "SELECT count(table_name) FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'foo';";
        $this->assertEquals($expect, $createTable->getSql());
    }

    public function testExecute()
    {
        $this->createTable('foo');

        $tableExists = new TableExistsCommand();
        $result = $tableExists
            ->setConnection($this->connection)
            ->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'))
            ->setTable(new Table('foo'))
            ->execute();
        $this->assertTrue($result);
    }

}
