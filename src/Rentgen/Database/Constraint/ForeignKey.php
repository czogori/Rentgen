<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\Table;

class ForeignKey implements ConstraintInterface
{    
    private $columns;
    private $table;
    private $referencedTable;

    /**
     * Constructor.
     *
     * @param Table $table           Table instance.
     * @param Table $referencedTable Table reference instance.
     */
    public function __construct(Table $table, Table $referencedTable)
    {
        $this->table = $table;
        $this->referencedTable = $referencedTable;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $tableName = $this->table->getName();
        $columnsAsString = implode('_', $this->columns);

        return $tableName . '_' . $columnsAsString . '_fkey';
    }

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set table instance.
     *
     * @param Table $table Table instance.
     *
     * @return PrimaryKey Self.
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get reference table name.
     *
     * @return string Reference table name.
     */
    public function getReferencedTable()
    {
        return $this->referencedTable;
    }

    /**
     * Set foreign key columns.
     *
     * @param array $columns Columns list.
     *
     * @return ForeignKey Self instance.
     */
    public function setColumns($columns)
    {
        if (!is_array($columns)) {
            $columns = array($columns);
        }
        $this->columns = $columns;

        return $this;
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

    /**
     * Set foreign key reference columns.
     *
     * @param array $columns Columns list.
     *
     * @return ForeignKey Self instance.
     */
    public function setReferencedColumns($columns)
    {
        if (!is_array($columns)) {
            $columns = array($columns);
        }
        $this->referencedColumns = $columns;

        return $this;
    }

    /**
     * Get reference column names.
     *
     * @return array Column names.
     */
    public function getReferencedColumns()
    {
        return $this->referencedColumns;
    }

    public function onUpdateNoAction()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onUpdateRestrict()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onUpdateCascade()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onUpdateSetNull()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onUpdateSetDefault()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onDeleteNoAction()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onDeleteRestrict()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onDeleteCascade()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onDeleteSetNull()
    {
        throw new Exception('Not implemented');

        return $this;
    }

    public function onDeleteSetDefault()
    {
        throw new Exception('Not implemented');

        return $this;
    }
}
