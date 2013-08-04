<?php
namespace Rentgen\Schema\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\Column;
use Rentgen\Event\ColumnEvent;

class AddColumnCommand extends Command
{
    private $table;
    private $column;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function setColumn(Column $column)
    {
        $this->column = $column;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s;'
            , $this->table->getName()
            , $this->column->getName()
            , $this->column->getType()
        );

        return $sql;
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {
        $this->dispatcher->dispatch('column.create', new ColumnEvent($this->table, $this->getSql()));
    }
}
