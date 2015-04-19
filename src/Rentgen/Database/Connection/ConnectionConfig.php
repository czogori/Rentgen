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
        $this->parseConfiguration($config);
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

    /**
     * Parse configuration.
     *
     * @param array $config
     * @return void
     */
    private function parseConfiguration(array $config)
    {
        $this->currentEnvironment = 'dev';
        foreach($config as $environment => $connection) {
            $this->dsn[$environment]= sprintf('%s:host=%s; port=%s; dbname=%s;',
                $connection['adapter'],
                $connection['host'],
                $connection['port'],
                $connection['database']);

            $this->adapter[$environment] = $connection['adapter'];
            $this->username[$environment] = $connection['username'];
            $this->password[$environment] = $connection['password'];
        }
    }
}
