<?php

namespace Rentgen\Database;

use Rentgen\Exception\NotSupportedException;

abstract class Column
{
    protected $name;    
    protected $isNotNull;
    protected $default;

    /**
     * Constructor.
     * 
     * @param string $name    Column name.     
     * @param array  $options Optional options.
     */
    public function __construct($name, array $options = array())
    {
        $this->name = $name;       

        if (array_key_exists('not_null', $options)) {
            $this->isNotNull = $options['not_null'];
        }
        if (array_key_exists('default', $options)) {
            $this->default = $options['default'];
        }
    }

    /**
     * Get column name.
     * 
     * @return string Column name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get default value of column.
     * 
     * @return mixed Default value of column.
     */
    public function getDefault()
    {
        return null;
    }
    
    /**
     * Check if column not allows null value.
     * 
     * @return boolean Column not allows null value.
     */
    public function isNotNull()
    {
        return false;
    }

    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    abstract public function getType();
}
