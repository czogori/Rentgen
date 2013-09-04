<?php

namespace Rentgen\Database;

class Column
{
    private $name;
    private $type;    
    private $isNotNull;
    private $default;
    private $limit;

    /**
     * Constructor.
     * 
     * @param string $name    Column name.
     * @param string $type    Column type name.
     * @param array  $options Optional options.
     */
    public function __construct($name, $type, array $options = array())
    {
        $this->name = $name;
        $this->type = $type;        

        if (array_key_exists('not_null', $options)) {
            $this->isNotNull = $options['not_null'];
        }
        if (array_key_exists('default', $options)) {
            $this->default = $options['default'];
        }
        if (array_key_exists('limit', $options)) {
            $this->default = $options['limit'];
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
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return $this->type;
    }    

    /**
     * Get default value of column.
     * 
     * @return mixed Default value of column.
     */
    public function getDefault()
    {
        return $this->default;
    }
    
    /**
     * [getLimit description]
     * 
     * @return [type] [description]
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Check if column not allows null value.
     * @return boolean Column not allows null value.
     */
    public function isNotNull()
    {
        return $this->isNotNull;
    }

}
