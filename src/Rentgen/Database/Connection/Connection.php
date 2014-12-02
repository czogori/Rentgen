<?php

namespace Rentgen\Database\Connection;

class Connection
{
    private $connection;
    private $config;

    /**
     * Constructor.
     *
     * @param ConnectionConfigInterface $config Connection config.
     */
    public function __construct(ConnectionConfigInterface $config)
    {
       $this->config = $config;
    }

    /**
     * Execute sql query.
     *
     * @param string $sql Sql query.
     *
     * @return integer
     */
    public function execute($sql)
    {
        return $this->getConnection()->exec($sql);
    }

    /**
     * Execute sql and expect return a data.
     *
     * @param string $sql Sql query.
     *
     * @return array
     */
    public function query($sql)
    {
        $rows = array();
        foreach ($this->getConnection()->query($sql) as $row) {
            $rows[] = $row;
        }

        return $rows;
    }

    private function getConnection()
    {
        if (null === $this->connection) {
            try {
                $this->connection = new \PDO($this->config->getDsn(), $this->config->getUsername(), $this->config->getPassword(),
                    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            } catch (\PDOException $exception) {
                throw new \InvalidArgumentException($exception->getMessage());
            }
        }

        return $this->connection;
    }
}
