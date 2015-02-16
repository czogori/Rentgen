<?php

namespace Rentgen\Database;

abstract class Column implements DatabaseObjectInterface
{
    protected $name;
    protected $description;
    protected $isNotNull = false;
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
        return $this->default;
    }

    /**
     * Check if column not allows null value.
     *
     * @return boolean Column not allows null value.
     */
    public function isNotNull()
    {
        return $this->isNotNull;
    }

    /**
     * Set the table instance.
     *
     * @param Table $table Table instance.
     *
     * @return void
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Get the table instance.
     *
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get column type name.
     *
     * @return string Column type name.
     */
    abstract public function getType();

    /**
     * Set column description.
     *
     * @param string $description Table description.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get column description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
