<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Column;
use Rentgen\Event\ColumnEvent;
use Rentgen\Schema\Adapter\Postgres\ColumnTypeMapper;

class AddColumnCommand extends Command
{
    private $column;

    public function setColumn(Column $column)
    {
        $this->column = $column;

        return $this;
    }

    public function getSql()
    {
        $columnTypeMapper = new ColumnTypeMapper();

        $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s;'
            , $this->column->getTable()->getQualifiedName()
            , $this->column->getName()
            , $columnTypeMapper->getNative($this->column->getType())
        );

        return $sql;
    }

    protected function postExecute()
    {
        $this->dispatcher->dispatch('column.add', new ColumnEvent($this->column, $this->getSql()));
    }
}
