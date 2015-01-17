<?php
namespace Rentgen\Schema\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;

class RenameTableCommand extends Command
{
    private $table;
    private $newTableName;

    /**
     * Sets a table.
     *
     * @param Table $table The table instance.
     *
     * @return RenameTableCommand
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Sets a table name.
     *
     * @param string $newTableName New table name.
     *
     * @return RenameTableCommand
     */
    public function setNewName($newTableName)
    {
        $this->newTableName = $newTableName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s RENAME TO %s;'
            , $this->table->getQualifiedName()
            , $this->newTableName
        );

        return $sql;
    }
}
