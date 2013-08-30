<?php

namespace Rentgen\Database;

class Column
{
    private $name;
    private $type;    
    private $isNotNull;
    private $default;

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
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }    

    public function getDefault()
    {
        return $this->default;
    }

    public function isNotNull()
    {
        return $this->isNotNull;
    }
}
