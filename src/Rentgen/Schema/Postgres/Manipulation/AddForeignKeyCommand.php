<?php
namespace Rentgen\Schema\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\ForeignKey;
use Rentgen\Database\Table;

class AddForeignKeyCommand extends Command
{
    private $foreignKey;

    public function setForeignKey(ForeignKey $foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s
                ADD CONSTRAINT %s FOREIGN KEY (%s)
                    REFERENCES %s (%s) MATCH SIMPLE
                    ON UPDATE CASCADE ON DELETE CASCADE;'
            , $this->table->getName()
            , $this->foreignKey->getName()
            , implode(',', $this->foreignKey->getColumns())
            , $this->foreignKey->getReferencedTable()->getName()
            , implode(',', $this->foreignKey->getReferencedColumns())
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
