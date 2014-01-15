<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\Table;

class ForeignKey implements ConstraintInterface
{

    const ACTION_NO_ACTION = 'NO ACTION';
    const ACTION_CASCADE   = 'CASCADE';
    const ACTION_RESTICT   = 'RESTRICT';
    const ACTION_DEFAULT   = 'DEFAULT';
    const ACTION_SET_NULL  = 'SET NULL';

    private $columns;
    private $table;
    private $referencedTable;
    private $updateAction;
    private $deleteAction;

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

        $this->updateAction = $this->deleteAction = self::ACTION_NO_ACTION;
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
     * Sets a table instance.
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
     * Gets a reference table name.
     *
     * @return string Reference table name.
     */
    public function getReferencedTable()
    {
        return $this->referencedTable;
    }

    /**
     * Sets foreign key columns.
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
     * Gets column names.
     *
     * @return array Column names.
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Sets foreign key reference columns.
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
     * Gets reference column names.
     *
     * @return array Column names.
     */
    public function getReferencedColumns()
    {
        return $this->referencedColumns;
    }

    /**
     * Gets an update action.
     *
     * @return string
     */
    public function getUpdateAction()
    {
        return $this->updateAction;
    }

    /**
     * Gets a delete action.
     *
     * @return string
     */
    public function getDeleteAction()
    {
        return $this->deleteAction;
    }

    /**
     * Sets an update action.
     *
     * @param string $updateAction Update action.
     *
     * @throws InvalidArgumentException If the provided argument is not of action type.
     *
     * @return void
     */
    public function setUpdateAction($updateAction)
    {
        $updateAction = strtoupper($updateAction);
        if(!in_array($updateAction, $this->getAvailableActions())) {
            throw new \InvalidArgumentException(sprintf('Action %s does not exist.', $updateAction));
        }
        $this->updateAction = $updateAction;
    }

    /**
     * Sets a delete action.
     *
     * @param string $deleteAction Delete action.
     *
     * @throws InvalidArgumentException If the provided argument is not of action type.
     *
     * @return void
     */
    public function setDeleteAction($deleteAction)
    {
        $deleteAction = strtoupper($deleteAction);
        if(!in_array($deleteAction, $this->getAvailableActions())) {
           throw new \InvalidArgumentException(sprintf('Action %s does not exist.', $deleteAction));
        }
        $this->deleteAction = $deleteAction;
    }

    /**
     * Sets an update action on restrict.
     *
     * @return ForeignKey
     */
    public function updateRestrict()
    {
        $this->updateAction = self::ACTION_RESTICT;

        return $this;
    }

    /**
     * Sets an update action on cascade.
     *
     * @return ForeignKey
     */
    public function updateCascade()
    {
        $this->updateAction = self::ACTION_CASCADE;

        return $this;
    }

    /**
     * Sets an update action on set null.
     *
     * @return ForeignKey
     */
    public function updateSetNull()
    {
        $this->updateAction = self::ACTION_SET_NULL;

        return $this;
    }

    /**
     * Sets an update action on default.
     *
     * @return ForeignKey
     */
    public function updateSetDefault()
    {
        $this->updateAction = self::ACTION_DEFAULT;

        return $this;
    }

    /**
     * Sets a delete action on restrict.
     *
     * @return ForeignKey
     */
    public function deleteRestrict()
    {
        $this->deleteAction = self::ACTION_RESTICT;

        return $this;
    }

    /**
     * Sets a delete action on cascade.
     *
     * @return ForeignKey
     */
    public function deleteCascade()
    {
        $this->deleteAction = self::ACTION_CASCADE;

        return $this;
    }

    /**
     * Sets a delete action on set null.
     *
     * @return ForeignKey
     */
    public function deleteSetNull()
    {
        $this->deleteAction = self::ACTION_SET_NULL;

        return $this;
    }

    /**
     * Sets a delete action on default.
     *
     * @return ForeignKey
     */
    public function deleteSetDefault()
    {
        $this->deleteAction = self::ACTION_DEFAULT;

        return $this;
    }

    private function getAvailableActions()
    {
        return array(
            ForeignKey::ACTION_NO_ACTION,
            ForeignKey::ACTION_CASCADE,
            ForeignKey::ACTION_RESTICT,
            ForeignKey::ACTION_DEFAULT,
            ForeignKey::ACTION_SET_NULL
        );
    }
}
