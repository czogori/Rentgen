<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Table;
use Rentgen\Database\Column;
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
}
