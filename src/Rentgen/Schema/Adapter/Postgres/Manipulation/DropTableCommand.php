<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Event\TableEvent;

class DropTableCommand extends Command
{
    private $table;
    private $cascade = false;

    /**
     * Sets a table.
     * 
     * @param Table $table The table instance.
     * 
     * @return DropTableCommand
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Drop table cascade.
     * 
     * @return DropTableCommand
     */
    public function cascade()
    {
        $this->cascade = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('DROP TABLE %s%s'
            , $this->table->getQualifiedName()
            , $this->cascade ? ' CASCADE' : ''
        );
        $sql .= ';';

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    protected function postExecute()
    {
        $this->dispatcher->dispatch('table.drop', new TableEvent($this->table, $this->getSql()));
    }
}
