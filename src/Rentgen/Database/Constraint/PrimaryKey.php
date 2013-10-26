<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\Table;

class PrimaryKey implements ConstraintInterface
{
    private $name;
    private $columns;
    private $isAutoIncrement = true;
    private $autoCreateColumn = false;
    private $table;

    public function __construct(array $columns = array())
    {
        $this->columns = $columns;
        if(empty($this->columns)) {
            $this->autoCreateColumn = true;         
        }
    }

    public function getName()
    {
        return $this->table->getName() . '_pkey';
    }

    public function getColumns()
    {
        return empty($this->columns)
            ? $this->table->getName() . '_id'
            : implode(',', $this->columns);        
    }

    public function isAutoCreateColumn()
    {
        return $this->autoCreateColumn;
    }

    public function isMulti()
    {
        return count($this->columns) > 1;
    }

    public function disableAutoIncrement()
    {
        $this->isAutoIncrement = false;

        return $this;
    }

    public function isAutoIncrement()
    {
        return true === $this->isAutoIncrement;
    }

    // TODO Remove this code
    public function __toString()
    {
        return  sprintf('CONSTRAINT %s PRIMARY KEY (%s)'
            , $this->getName()
            , $this->getColumns()
        );
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable(Table $table)
    {
        $this->table = $table;
    }
}
