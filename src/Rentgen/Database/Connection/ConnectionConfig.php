<?php

namespace Rentgen\Database\Connection;

class ConnectionConfig implements ConnectionConfigInterface
{
    private $adapter;
    private $username;
    private $password;

    public function __construct(array $config = array())
    {
        if(isset($config['dsn'])) {
            $this->dsn = $config['dsn'];            
            $config = array_merge($config, $this->parseDsn($config['dsn']));
        } else {
            $this->dsn = sprintf('%s:host=%s; port=%s; dbname=%s;'
                , $config['adapter']
                , $config['host']
                , $config['port']
                , $config['database']);      
            
        }   
        $this->adapter = $config['adapter'];     
        $this->username = $config['username'];
        $this->password = $config['password'];        
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
        return $this->dsn;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    private function parseDsn($dsn)
    {
        $config = array();
        if(preg_match('/^(.*):/', $dsn, $matches)) {
            $config['adapter'] = $matches[1];            
        }
        return $config;
    }
}
