<?php

namespace Rentgen\Tests\Schema\Postgres\Manipulation;

use Rentgen\Schema\Adapter\Postgres\Manipulation\DropConstraintCommand;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Table;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class DropConstraintCommandTest extends TestHelpers
{
    public function setUp()
    {
        $this->clearDatabase();
    }

    public function testDropForeignKeyGetSql()
    {
        $foreignKey = new ForeignKey(new Table('foo'), new Table('bar'));
        $foreignKey->setColumns('column_foo');

        $dropConstraintCommand = new DropConstraintCommand();
        $dropConstraintCommand->setConstraint($foreignKey);
        $this->assertEquals('ALTER TABLE foo DROP CONSTRAINT foo_column_foo_fkey;', $dropConstraintCommand->getSql());
    }
}
