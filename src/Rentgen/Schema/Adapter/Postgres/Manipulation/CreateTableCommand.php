<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\Column;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Adapter\Postgres\ColumnTypeMapper;

class CreateTableCommand extends Command
{
    private $table;

    /**
     * Sets a table.
     * 
     * @param Table $table The table instance.
     * 
     * @return CreateTableCommand
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('CREATE TABLE %s(%s);'
            , $this->table->getQualifiedName()
            , $this->getColumnsSql() . $this->getConstraintsSql());

        return $sql;
    }

    /**
     * Gets constraints query.
     * 
     * @return string
     */
    private function getConstraintsSql()
    {        
        $sql = '';
        foreach ($this->table->getConstraints() as $constraint) {
            $sql .= ',';
            if ($constraint instanceof PrimaryKey) {
                $sql .= (string) $constraint ;
            } else if ($constraint instanceof ForeignKey) {
                $sql .= sprintf('CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION'
                    , $constraint->getName()
                    , implode(',', $constraint->getColumns())
                    , $constraint->getReferencedTable()->getQualifiedName()
                    , implode(',', $constraint->getReferencedColumns()));
            } else if ($constraint instanceof Unique) {
                $sql .= sprintf('CONSTRAINT %s UNIQUE (%s)'
                    , $constraint->getName()
                    , implode(',', $constraint->getColumns()));
            }
        }
        return rtrim($sql, ',');
    }

    /**
     * Gets columns query.
     * 
     * @return string
     */
    private function getColumnsSql()
    {
        $columnTypeMapper = new ColumnTypeMapper();

        foreach ($this->table->getConstraints() as $constraint) {
            if ($constraint instanceof PrimaryKey) {
                $primaryKey = $constraint;
            }
        }
        if (!isset($primaryKey)) { // TODO find better solution
            $primaryKey = new PrimaryKey();
            $primaryKey->setTable($this->table);
            $this->table->addConstraint($primaryKey);
        }

        $sql = '';
        if (!$primaryKey->isMulti() && $primaryKey->isAutoCreateColumn()) {
            $sql = sprintf('%s %s NOT NULL,', $primaryKey->getColumns(), $primaryKey->isAutoIncrement() ? 'serial' : 'integer');
        }
        foreach ($this->table->getColumns() as $column) {
            $sql .= sprintf('%s %s%s %s %s,'
                , $column->getName()
                , $columnTypeMapper->getNative($column->getType())
                , $column->getType() === 'string' && $column->getLimit() ? sprintf('(%s)', $column->getLimit()) : ''
                , $column->isNotNull() ? 'NOT NULL' : ''
                , null === $column->getDefault() ? '' : 'DEFAULT'. ' ' . $this->addQuotesIfNeeded($column, $column->getDefault())
            );
        }        

        return rtrim($sql, ',');
    }

    /**
     * {@inheritdoc}
     */
    protected function postExecute()
    {
        $this->dispatcher->dispatch('table.create', new TableEvent($this->table, $this->getSql()));
    }

    
    private function addQuotesIfNeeded(Column $column, $value)
    {
        return $column->getType() === 'string' ? sprintf("'%s'", $value) : $value;
    }
}
