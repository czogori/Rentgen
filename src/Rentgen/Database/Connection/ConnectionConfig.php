<?php

namespace Rentgen\Database\Connection;

class ConnectionConfig implements ConnectionConfigInterface
{
    private $adapter;
    private $host;
    private $database;
    private $username;
    private $password;
    private $port;

    public function __construct(array $config = array())
    {
        $this->adapter = $config['adapter'];
        $this->host = $config['host'];
        $this->database = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->port = $config['port'];
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDsn()
    {
        return sprintf('%s:host=%s; port=%s; dbname=%s;'
            , $this->adapter
            , $this->host
            , $this->port
            , $this->database);
    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}
