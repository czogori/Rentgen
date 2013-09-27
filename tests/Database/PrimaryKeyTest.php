<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Table;
use Rentgen\Database\Constraint\PrimaryKey;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class PrimaryKeyTest extends \PHPUnit_Framework_TestCase
{
    public function testPrimaryKey()
    {
        $primaryKey = new PrimaryKey();
        $primaryKey->setTable(new Table('foo'));
        $this->assertEquals('CONSTRAINT foo_pkey PRIMARY KEY (foo_id)', (string) $primaryKey);
    }

    public function testMultiPrimaryKey()
    {
        $primaryKey = new PrimaryKey(array('foo', 'bar'));
        $primaryKey->setTable(new Table('test'));
        $this->assertEquals('CONSTRAINT test_pkey PRIMARY KEY (foo,bar)', (string) $primaryKey);
    }

    public function testIfIsMultiPrimaryKey()
    {
        $primaryKey = new PrimaryKey(array('foo', 'bar'));
        $this->assertTrue($primaryKey->isMulti());
    }

    public function testIfIsNotMultiPrimaryKey()
    {
        $primaryKey = new PrimaryKey(array('foo'));
        $this->assertFalse($primaryKey->isMulti());
    }
}
