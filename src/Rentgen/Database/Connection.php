<?php

namespace Rentgen\Database;

class Connection
{

    private $connection;
    private $config;

    public function __construct(ConnectionConfigInterface $config)
    {
        try {
            $this->connection = new \PDO($config->getDsn(), $config->getLogin(), $config->getPassword(), array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        } catch (\PDOException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }
    }

    public function execute($sql)
    {
        return $this->connection->exec($sql);
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }
}
