<?php
namespace Rentgen\Schema\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\PrimaryKey;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Postgres\ColumnTypeMapper;

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
        if(!$this->primaryKey) {
            $this->primaryKey = new PrimaryKey();
            $this->primaryKey->setTable($this->table);
        }

        $sql = '';        
        if(!$this->primaryKey->isMulti()) {
            $sql = sprintf('%s serial NOT NULL,', $this->primaryKey->getColumns());    
        }         
        foreach ($this->table->columns as $column) {
            $sql .= sprintf('%s %s %s,'
                , $column->getName()
                , $columnTypeMapper->getNative($column->getType())
                , $column->isNull() ? '' : 'NOT NULL'
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
}

