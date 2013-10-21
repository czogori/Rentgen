<?php

namespace Rentgen\Database;

class Table
{
    private $name;
    private $schemaName;
    protected $columns = array();
    protected $constraints = array();

    /**
     * Constructor.
     *
     * @param string $name Table name.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get table name.
     *
     * @return string Table name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get qualified name (schema name + table name)
     * 
     * @return string  Qualified name.
     */
    public function getQualifiedName()
    {
        return $this->getSchema() . '.' .$this->name;
    }

    /**
     * Add column to table.
     *
     * @param Column $column Column instance.
     *
     * @return Table Table instance.
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Get columns of table.
     *
     * @return Column[] Array of Column instances.
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get column of table.
     *
     * @param  string $name Column name.
     * @return Column Column instance.
     */
    public function getColumn($name)
    {
        foreach ($this->columns as $column) {
            if ($column->getName() == $name) {
                return $column;
            }
        }

        return null;
    }

    /**
     * Add contraint to table.
     *
     * @param ConstraintInterface $constraint Instance of class implements ConstraintInterface.
     *
     * @return Table Table instance.
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    /**
     * Get constraints of table.
     *
     * @return ConstraintInterface[] Array of instances implement ConstraintInterface.
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Set schema name.
     *
     * @param string $name Schama name.
     */
    public function setSchema($name)
    {
        $this->schemaName = $name;
    }

    /**
     * Get schema name.
     *
     * @return string Schema name.
     */
    public function getSchema()
    {
        return $this->schemaName ?: 'public';
    }
}
