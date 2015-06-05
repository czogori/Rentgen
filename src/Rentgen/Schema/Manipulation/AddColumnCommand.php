<?php
namespace Rentgen\Schema\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Column;
use Rentgen\Database\Column\CustomColumn;
use Rentgen\Event\ColumnEvent;
use Rentgen\Schema\ColumnTypeMapper;

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

        $columnDescription = $this->column->getDescription();
        if (!empty($tableDescription)) {
            $sql .= sprintf("COMMENT ON COLUMN %s.%s IS '%s';",
                $this->column->getTable()->getQualifiedName(),
                $this->column->getName(),
                $columnDescription);
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
