<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\ForeignKey;
use Rentgen\Database\Table;

class AddForeignKeyCommand extends Command
{
    private $foreignKey;

    /**
     * Set foreign key
     * 
     * @param ForeignKey $foreignKey Foreign key instance.
     *
     * @return AddForeignKeyCommand
     */
    public function setForeignKey(ForeignKey $foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * Set table witch foreign key will be added.
     * 
     * @param Table $table Table instance.
     *
     * @return AddForeignKeyCommand
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get sql query.
     *
     * @return string Sql query.
     */
    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE;'
            , $this->foreignKey->getTable()->getName()
            , $this->foreignKey->getName()
            , implode(',', $this->foreignKey->getColumns())
            , $this->foreignKey->getReferencedTable()->getName()
            , implode(',', $this->foreignKey->getReferencedColumns())
        );
        return $sql;
    }    
}
