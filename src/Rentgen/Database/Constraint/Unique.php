<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\Table;

class Unique implements ConstraintInterface
{
    private $name;
    private $columns;
    private $table;

    public function __construct(Table $table, $columns)
    {
        $this->table = $table;        
        $this->columns = is_string($columns) ? array($columns) : $columns;
    }

    public function getName()
    {
        $tableName = $this->table->getName();
        $columnsAsString = implode('_', $this->columns);
        return $tableName . '_' . $columnsAsString . '_key';
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
