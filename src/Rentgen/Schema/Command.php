<?php
namespace Rentgen\Schema;

use Rentgen\Database\Connection\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class Command
{
    protected $connection;
    protected $dispatcher;

    /**
     * Execute query.
     *
     * @return integer
     */
    public function execute()
    {
        $this->preExecute();
        $result = $this->connection->execute($this->getSql());
        $this->postExecute();

        return $result;
    }

    /**
     * Set an event dispatcher.
     *
     * @param EventDispatcher $dispatcher EventDispatcher instance.
     *
     * @return Command
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Set the connection.
     *
     * @param Connection $connection Connection instance.
     *
     * @return Command
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Execute actions before execute query.
     *
     * @return void
     */
    protected function preExecute()
    {
    }

    /**
     * Execute actions after execute query.
     *
     * @return void
     */
    protected function postExecute()
    {
    }

    /**
     * Get sql query to execute.
     *
     * @return string
     */
    abstract public function getSql();
}
