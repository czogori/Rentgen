<?php

namespace Rentgen\Database\Connection;

class Connection
{
    private $connection;

    /**
     * Constructor.
     *
     * @param ConnectionConfigInterface $config Connection config.
     */
    public function __construct(ConnectionConfigInterface $config)
    {
        try {
            $this->connection = new \PDO($config->getDsn(), $config->getUsername(), $config->getPassword(),
                array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        } catch (\PDOException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * Execute sql query.
     *
     * @param string  $sql Sql query.
     *
     * @return integer
     */
    public function execute($sql)
    {
        return $this->connection->exec($sql);
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
        foreach ($this->connection->query($sql) as $row) {
            $rows[] = $row;
        }

        return $rows;
    }
}
