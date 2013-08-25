<?php

namespace Rentgen\Database;

class PrimaryKey implements ConstraintInterface
{
    private $name;
    private $columns;

    private $table;

    public function __construct(array $columns = array())
    {        
    	$this->columns = $columns;
    }        
	
	public function getName()
    {
        return $this->table->getName() . '_pkey';
    }

    public function getColumns()
    {
        return $this->isMulti()
            ?  implode(',', $this->columns)
            :  $this->table->getName() . '_id';
    }

    public function isMulti()
    {
        return count($this->columns) > 0;
    }

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
