<?php
namespace Rentgen\Schema\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Postgres\ColumnTypeMapper;

class CreateTableCommand extends Command
{
    private $table;
    private $multiPrimaryKey;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function withMultiPrimaryKey(array $keys = array())
    {
        $this->multiPrimaryKey = $keys;
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
        $sql = '';
        
        $primaryKeyColumns = $this->getPrimaryKeyColumns();        
        if(!$this->isMultiPrimaryKey()) {
            $sql = sprintf('%s serial NOT NULL,', $primaryKeyColumns);    
        }         
        foreach ($this->table->columns as $column) {
            $sql .= sprintf('%s %s %s,'
                , $column->getName()
                , $columnTypeMapper->getNative($column->getType())
                , $column->isNull() ? '' : 'NOT NULL'
            );
        }
        $sql .= sprintf('CONSTRAINT %s PRIMARY KEY (%s),'
            , $this->getPrimaryKeyName()
            , $primaryKeyColumns
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

    private function getPrimaryKeyName()
    {
        return $this->table->getName() . '_pkey';
    }

    private function getPrimaryKeyColumns()
    {
        return $this->isMultiPrimaryKey()
            ?  implode(',', $this->multiPrimaryKey)
            :  $this->table->getName() . '_id';
    }

    private function isMultiPrimaryKey()
    {
        return is_array($this->multiPrimaryKey) && count($this->multiPrimaryKey) > 0;
    }
}
