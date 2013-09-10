<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Event\TableEvent;
use Rentgen\Schema\Adapter\Postgres\Info\GetTablesCommand;

class DropAllTablesCommand extends Command
{
    private $schemaName = 'public';
    private $sql = '';

    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function execute()
    {
        $getTablesCommand = new GetTablesCommand();
        $getTablesCommand->setConnection($this->connection);
        $tables = $getTablesCommand->execute();

        $this->preExecute();
        foreach ($tables as $table) {
            $dropTable = new DropTableCommand();
            $dropTable
                ->setTable($table)
                ->cascade()
                ->setConnection($this->connection)
                ->setEventDispatcher($this->dispatcher);
            $this->sql .= $dropTable->getSql();
            $dropTable->execute();
        }
        $this->postExecute();

        return true;
    }

    protected function preExecute()
    {

    }

    protected function postExecute()
    {
        //$this->dispatcher->dispatch('table.drop_all', new TableEvent($this->table, $this->sql()));
    }
}
