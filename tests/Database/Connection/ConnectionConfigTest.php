<?php

namespace Rentgen\Tests\Database\Connection;

use Rentgen\Database\Connection\ConnectionConfig;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ConnectionConfigTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->config = array(
            'adapter' => 'pgsql',
            'host' => 'localhost',
            'database' => 'rentgen',
            'port' => '5432',
            'username' => 'test',
            'password' => 'qwerty',            
        );
    }

    public function testGetUsername()
    {               
        $connectionConfig = new ConnectionConfig($this->config);
        $this->assertEquals('test', $connectionConfig->getUsername());
    }

    public function testGetPassword()
    {               
        $connectionConfig = new ConnectionConfig($this->config);
        $this->assertEquals('qwerty', $connectionConfig->getPassword());
    }

    public function testGetDsn()
    {               
        $connectionConfig = new ConnectionConfig($this->config);
        $this->assertEquals('pgsql:host=localhost; port=5432; dbname=rentgen;', $connectionConfig->getDsn());
    }

    public function testGetAdapter()
    {               
        $connectionConfig = new ConnectionConfig($this->config);
        $this->assertEquals('pgsql', $connectionConfig->getAdapter());
    }
}
