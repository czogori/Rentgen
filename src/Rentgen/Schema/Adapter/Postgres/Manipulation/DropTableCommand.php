<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Event\TableEvent;

class DropTableCommand extends Command
{
    private $table;
    private $cascade = false;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function cascade()
    {
        $this->cascade = true;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('DROP TABLE %s%s'
            , $this->table->getName()
            , $this->cascade ? ' CASCADE' : ''
        );
        $sql .= ';';

        return $sql;
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {
        $this->dispatcher->dispatch('table.drop', new TableEvent($this->table, $this->getSql()));
    }
}
