<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Schema\Adapter\Postgres\Info\GetTablesCommand;

class DropAllTablesCommand extends Command
{
    private $schemaName = 'public';
    private $sql = '';

    /**
     * Sets a schema name.
     *
     * @param string $schemaName The schema name.
     *
     * @return DropAllTablesCommand
     */
    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * {@inheritdoc}
     */
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
}
