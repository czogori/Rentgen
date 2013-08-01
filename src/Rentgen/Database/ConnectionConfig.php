<?php

namespace Rentgen\Database;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

class ConnectionConfig implements ConnectionConfigInterface
{
    private $host;
    private $database;
    private $login;
    private $password;
    private $port;

    public function __construct()
    {
        $fileLocator = new FileLocator(getcwd());
        $configFile = $fileLocator->locate('config.yml');        
        $config = Yaml::parse($configFile);
        $configArray = $config['connection'];

        $this->host = $configArray['host'];
        $this->database = $configArray['database'];
        $this->login = $configArray['user'];
        $this->password = $configArray['password'];
        $this->port = $configArray['port'];
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDsn()
    {
        return sprintf('pgsql:host=%s; port=%s; dbname=%s;'
            , $this->host
            , $this->port
            , $this->database);
    }
}
