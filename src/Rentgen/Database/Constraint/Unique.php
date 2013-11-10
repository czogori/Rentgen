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
     * @param array|string $columns Column names.
     * @param datatype     $table   Table instance.
     */
    public function __construct($columns, Table $table)
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
