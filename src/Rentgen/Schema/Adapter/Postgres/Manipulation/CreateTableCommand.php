<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\Column;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Adapter\Postgres\ColumnTypeMapper;

class CreateTableCommand extends Command
{
    private $table;
    private $primaryKey;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function setPrimaryKey(PrimaryKey $primaryKey)
    {
        $this->primaryKey = $primaryKey;
        $this->primaryKey->setTable($this->table);

        return $this;
    }

    public function getSql()
    {        
        $sql = sprintf('CREATE TABLE %s(%s);'     
            , $this->table->getQualifiedName()
            , $this->columns());

        return $sql;
    }

    private function columns()
    {
        $columnTypeMapper = new ColumnTypeMapper();

        foreach ($this->table->getConstraints() as $constraint) {
            if($constraint instanceof PrimaryKey) {
                $this->primaryKey = $constraint;
            }
        }
        if (!$this->primaryKey) {
            $this->primaryKey = new PrimaryKey();
            $this->primaryKey->setTable($this->table);
        }

        $sql = '';
        if (!$this->primaryKey->isMulti()) {
            if ($this->primaryKey->isAutoIncrement()) {
                $sql = sprintf('%s serial NOT NULL,', $this->primaryKey->getColumns());
            }
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
        $sql .= (string) $this->primaryKey;
        return $sql;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
        $this->dispatcher->dispatch('table.create', new TableEvent($this->table, $this->getSql()));
    }

    private function addQuotesIfNeeded(Column $column, $value)
    {
        return $column->getType() === 'string' ? sprintf("'%s'", $value) : $value;
    }
}