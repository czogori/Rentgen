<?php

namespace Rentgen\Database;

class ForeignKey implements ConstraintInterface
{
    private $name;
    private $columns;

    private $table;

    public function __construct(Table $table, Table $referencedTable)
    {
        $this->table = $table;
        $this->referencedTable = $referencedTable;
    }

    public function getName()
    {
        $tableName = $this->table->getName();
        $columnsAsString = implode('_', $this->columns);

        return $tableName . '_' . $columnsAsString . '_fkey';
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable(Table $table)
    {
        $this->table = $table;        
    }

    public function getReferencedTable()
    {
        return $this->referencedTable;
    }

    public function setReferencedTable(Table $referencedTable)
    {
        $this->referencedTable = $referencedTable;
    }

    public function setColumns($columns)
    {
        if(!is_array($columns)) {
            $columns = array($columns);    
        }
        $this->columns = $columns;
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setReferencedColumns($columns)
    {
        if(!is_array($columns)) {
            $columns = array($columns);    
        }
        $this->referencedColumns = $columns;

        return $this;
    }

    public function getReferencedColumns()
    {
        return $this->referencedColumns;
    }

    public function onUpdateNoAction()
    {
        return $this;
    }

    public function onUpdateRestrict()
    {
        return $this;
    }

    public function onUpdateCascade()
    {
        return $this;
    }

    public function onUpdateSetNull()
    {
        return $this;
    }

    public function onUpdateSetDefault()
    {
        return $this;
    }

    public function onDeleteNoAction()
    {
        return $this;
    }

    public function onDeleteRestrict()
    {
        return $this;
    }

    public function onDeleteCascade()
    {
        return $this;
    }

    public function onDeleteSetNull()
    {
        return $this;
    }

    public function onDeleteSetDefault()
    {
        return $this;
    }
}
