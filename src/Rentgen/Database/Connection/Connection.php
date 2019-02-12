<?php

namespace Rentgen\Database\Connection;

use Rentgen\Event\SqlEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Connection
{
    private $connection;
    private $config;
    private $dispatcher;

    /**
     * Constructor.
     *
     * @param ConnectionConfigInterface $config     Connection config.
     * @param EventDispatcherInterface  $dispatcher Event dispatcher.
     */
    public function __construct(ConnectionConfigInterface $config, EventDispatcherInterface $dispatcher)
    {
       $this->config = $config;
       $this->dispatcher = $dispatcher;
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
        $this->dispatcher->dispatch('rentgen.sql_executed', new SqlEvent($sql));

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
