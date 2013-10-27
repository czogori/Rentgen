<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\Table;

class Unique implements ConstraintInterface
{
    private $name;
    private $columns;
    private $table;

    /**
     * Constructor.
     * 
     * @param datatype $table Table instance.
     * @param array    $columns Column names.
     */
    public function __construct(Table $table, $columns)
    {
        $this->table = $table;        
        $this->columns = is_string($columns) ? array($columns) : $columns;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $tableName = $this->table->getName();
        $columnsAsString = implode('_', $this->columns);
        return $tableName . '_' . $columnsAsString . '_key';
    }

    /**
     * {@inheritdoc}
     */
    
    public function getTable()
    {
        return $this->table;
    }

   /**
     * Get column names.
     * 
     * @return array Column names.
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
