<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;

class RenameTableCommand extends Command
{
    private $table;
    private $newTableName;

    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }    

    public function setNewName($newTableName)
    {
        $this->newTableName = $newTableName;
        return $this;
    }

    public function getSql()
    {
        $schemaName = $this->table->getSchema();
        $schema = empty($schemaName) ? 'public' : $schemaName;

        $sql = sprintf('ALTER TABLE %s.%s RENAME TO %s;'
            , $schema
            , $this->table->getName()
            , $this->newTableName
        );    
        return $sql;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {        
    }
}
