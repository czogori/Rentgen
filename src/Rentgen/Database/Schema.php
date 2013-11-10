<?php

namespace Rentgen\Database;

class Schema
{
    private $name;

    /**
     * Constructor.
     *
     * @param string $name Schema name.
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Get schema name.
     *
     * @return string Schema name.
     */
    public function getName()
    {
        return $this->name ?: 'public'; //TODO to remove
    }
}
