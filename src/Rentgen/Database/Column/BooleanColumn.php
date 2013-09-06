<?php

namespace Rentgen\Database\Column;

class BooleanColumn
{
    private $name;
    private $type;    
    private $isNotNull;
    private $default;
    private $limit;

    
    public function __construct($name, array $options = array())
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
            $this->limit = $options['limit'];
        }
    }    

    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return 'boolean';
    }    

    /**
     * Get default value of column.
     * 
     * @return mixed Default value of column.
     */
    public function getDefault()
    {
        return null === $this->default ?: (string) $this->default;
    }    
}
