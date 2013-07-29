<?php
namespace Rentgen\Adapter\Postgres\Command;

abstract class Command
{
    private $connection;

    public function execute()
    {
        $this->preExecute();
        echo $this->sql();
        $this->postExecute();
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {

    }

    abstract protected function sql();
}
