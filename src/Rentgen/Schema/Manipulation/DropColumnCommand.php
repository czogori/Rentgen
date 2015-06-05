<?php
namespace Rentgen\Schema\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\Column;

class DropColumnCommand extends Command
{
    private $column;

    /**
     * Set column to drop from a table.
     *
     * @param Column $column Column instance.
     *
     * @return DropColumnCommand Self.
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
        $sql = sprintf('ALTER TABLE %s DROP COLUMN %s;'
            , $this->column->getTable()->getQualifiedName()
            , $this->column->getName()
        );

        return $sql;
    }
}
