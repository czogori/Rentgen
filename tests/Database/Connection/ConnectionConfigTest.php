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
        $username = 'test';
        $password = 'qwerty';

        $this->config = array(
            'adapter' => 'pgsql',
            'host' => 'localhost',
            'database' => 'rentgen',
            'port' => '5432',
            'username' => $username,
            'password' => $password,
        );
        $this->configWithDsn = array(
            'dsn' => 'pgsql:host=localhost; port=5432; dbname=rentgen;',
            'username' => $username,
            'password' => $password,
        );

        $this->configs = array($this->config, $this->configWithDsn);
    }

    public function testGetUsername()
    {
        foreach ($this->configs as $config) {
            $connectionConfig = new ConnectionConfig($config);
            $this->assertEquals('test', $connectionConfig->getUsername());
        }
    }

    public function testGetPassword()
    {
        foreach ($this->configs as $config) {
            $connectionConfig = new ConnectionConfig($config);
            $this->assertEquals('qwerty', $connectionConfig->getPassword());
        }
    }

    public function testGetDsn()
    {
        foreach ($this->configs as $config) {
            $connectionConfig = new ConnectionConfig($config);
            $this->assertEquals('pgsql:host=localhost; port=5432; dbname=rentgen;', $connectionConfig->getDsn());
        }
    }

    public function testGetAdapter()
    {
        foreach ($this->configs as $config) {
            $connectionConfig = new ConnectionConfig($config);
            $this->assertEquals('pgsql', $connectionConfig->getAdapter());
        }
    }
}
