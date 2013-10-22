<?php

namespace Rentgen\Tests\Database;

use Rentgen\Database\Schema;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class SchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstanceWithoutSchemaName()
    {
        $schema = new Schema();        
        $this->assertEquals('public', $schema->getName());
    }

    public function testCreateInstanceWithSchemaName()
    {
        $schema = new Schema('foo');        
        $this->assertEquals('foo', $schema->getName());
    }
}
