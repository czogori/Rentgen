<?php

namespace Rentgen\Database;

class Index
{    
    private $columns = array();
    private $table;

    /**
     * Constructor.
     *
     * @param array|string $columns Columns of index.
     * @param Table        $table   Table instance.
     */
    public function __construct($columns, Table $table)
    {
        $this->columns = is_string($columns) ? array($columns) : $columns;        
        $this->table = $table;
    }

    /**
     * Get index name.
     * 
     * @return string Index name.
     */
    public function getName()
    {
        return $this->table->getName() . '_' .  implode('_', $this->columns) . '_idx';
    }

    /**
     * Get column names of index.
     *
     * @return string[] Array of Column instances.
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get table instance. 
     * 
     * @return Table Table instance.
     */
    public function getTable()
    {        
        return $this->table;
    }

    
}
