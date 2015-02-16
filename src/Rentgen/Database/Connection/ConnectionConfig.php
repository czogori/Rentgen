<?php

namespace Rentgen\Database\Connection;

class ConnectionConfig implements ConnectionConfigInterface
{
    private $adapter;
    private $username;
    private $password;

    /**
     * Constructor.
     *
     * @param array $config Array config.
     */
    public function __construct(array $config = array())
    {
        $this->configure($config);
    }

    /**
     * {@inheritdoc}
     */
    public function changeEnvironment($environment)
    {
        $this->currentEnvironment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username[$this->currentEnvironment];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password[$this->currentEnvironment];
    }

    /**
     * {@inheritdoc}
     */
    public function getDsn()
    {
        return $this->dsn[$this->currentEnvironment];
    }

    /**
     * Get database adapter.
     *
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter[$this->currentEnvironment];
    }

    private function configure(array $config)
    {
        $this->currentEnvironment = 'dev';
        foreach($config as $environment => $connection) {
            if (isset($connection['dsn'])) {
                $this->dsn[$environment]  = $connection['dsn'];
                //$connection = array_merge($connection, $this->parseDsn($connection['dsn']));
            } else {
                $this->dsn[$environment]= sprintf('pgsql:host=%s; port=%s; dbname=%s;'
                    , $connection['host']
                    , $connection['port']
                    , $connection['database']);

            }
            //$this->adapter[$environment] = $connection['adapter'];
            $this->username[$environment] = $connection['username'];
            $this->password[$environment] = $connection['password'];
        }
    }

    /**
     * Parse DSN string to arrray.
     *
     * @param string $dsn Dsn string.
     *
     * @return array
     */
    private function parseDsn($dsn)
    {
        $config = array();
        if (preg_match('/^(.*):/', $dsn, $matches)) {
            $config['adapter'] = $matches[1];
        }

        return $config;
    }
}
