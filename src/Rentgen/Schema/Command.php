<?php
namespace Rentgen\Schema;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Rentgen\Database\Connection;

abstract class Command
{
    protected $connection;
    protected $dispatcher;

    public function execute()
    {

        $this->preExecute();
        $result = $this->connection->execute($this->getSql());
        $this->postExecute();

        return $result;
    }

    public function setEventDispatcher(EventDispatcher $dispatcher)
    {        
        $this->dispatcher = $dispatcher;

        return $this;
    }

    public function setConnection(Connection $connection)
    {        
        $this->connection = $connection;

        return $this;
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {
    }

    abstract public function getSql();
}
