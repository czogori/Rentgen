<?php

namespace Rentgen\Database;

class Column
{
    private $name;
    private $type;
    private $isNull;

    public function __construct($name, $type, $isNull = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isNull = $isNull;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isNull()
    {
        return $this->isNull;
    }

    public function getDefault()
    {

    }
}
