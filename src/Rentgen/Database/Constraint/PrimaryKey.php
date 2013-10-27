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

    /**
     * Constructor.
     * 
     * @param array $columns Column names.
     */
    public function __construct(array $columns = array())
    {
        $this->columns = $columns;
        if(empty($this->columns)) {
            $this->autoCreateColumn = true;         
        }
    }

    /**
     * {@inheritdoc}
     */    
    public function getName()
    {
        return $this->table->getName() . '_pkey';
    }

    // TODO hmmmmmm....
    public function getColumns()
    {
        return empty($this->columns)
            ? $this->table->getName() . '_id'
            : implode(',', $this->columns);        
    }

    /**
     * Return true if the primary key is auto create column.
     * 
     * @return bool Auto create column
     */
    public function isAutoCreateColumn()
    {
        return $this->autoCreateColumn;
    }

    /**
     * Return true if the primary key is multi.
     * 
     * @return bool Multi columns.
     */
    public function isMulti()
    {
        return count($this->columns) > 1;
    }

    /**
     * Disable auto increment.
     * 
     * @return PrimaryKey Self.
     */
    public function disableAutoIncrement()
    {
        $this->isAutoIncrement = false;

        return $this;
    }

    /**
     * Return true if the primary key is auto increment.
     * 
     * @return bool Auto increment.
     */
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

    /**
     * {@inheritdoc}
     */    
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set table instance.
     * 
     * @param Table $table Table instance.
     * 
     * @return PrimaryKey Self.
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }
}
