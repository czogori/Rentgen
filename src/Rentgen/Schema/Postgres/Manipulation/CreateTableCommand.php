<?php
namespace Rentgen\Schema\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Postgres\ColumnTypeMapper;

class CreateTableCommand extends Command
{
    private $table;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getSql()
    {
        $schema = $this->table->getSchema() == '' ? '' : $this->table->getSchema().'.';
        $sql = sprintf('CREATE TABLE %s%s(%s);'
            , $schema
            , $this->table->getName()
            , $this->columns());

        return $sql;
    }

    private function columns()
    {
        $columnTypeMapper = new ColumnTypeMapper();

        $primaryKeyName = $this->table->getName() . '_id';
        $sql = sprintf('%s serial NOT NULL,', $primaryKeyName);
        foreach ($this->table->columns as $column) {
            $sql .= sprintf('%s %s %s,'
                , $column->getName()
                , $columnTypeMapper->getNative($column->getType())
                , $column->isNull() ? '' : 'NOT NULL'
            );
        }
        $sql .= sprintf('CONSTRAINT %s PRIMARY KEY (%s),'
            , $this->table->getName() . '_pkey'
            , $primaryKeyName
        );

        $sql = trim($sql, ',');

        return $sql;
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {
        $this->dispatcher->dispatch('table.create', new TableEvent($this->table, $this->getSql()));
    }
}
