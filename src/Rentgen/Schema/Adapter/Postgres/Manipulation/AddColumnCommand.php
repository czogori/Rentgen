<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Column;
use Rentgen\Database\Column\CustomColumn;
use Rentgen\Event\ColumnEvent;
use Rentgen\Schema\Adapter\Postgres\ColumnTypeMapper;

class AddColumnCommand extends Command
{
    private $column;

    /**
     * Sets a column.
     *
     * @param Column $column The column instance.
     *
     * @return AddColumnCommand
     */
    public function setColumn(Column $column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        if ($this->column instanceof CustomColumn) {
            $columnType = $this->column->getType();
        } else {
            $columnTypeMapper = new ColumnTypeMapper();
            $columnType = $columnTypeMapper->getNative($this->column->getType());
        }

        $sql = sprintf('ALTER TABLE %s ADD COLUMN %s %s;'
            , $this->column->getTable()->getQualifiedName()
            , $this->column->getName()
            , $columnType
        );

        if (!empty($this->column->getDescription())) {
            $sql .= sprintf("COMMENT ON COLUMN %s.%s IS '%s';",
                $this->column->getTable()->getQualifiedName(),
                $this->column->getName(),
                $this->column->getDescription());
        }
        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    protected function postExecute()
    {
        $this->dispatcher->dispatch('column.add', new ColumnEvent($this->column, $this->getSql()));
    }
}
