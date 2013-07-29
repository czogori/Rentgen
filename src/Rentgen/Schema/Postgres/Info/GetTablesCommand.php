<?php
namespace Rentgen\Schema\Postgres\Info;

use Rentgen\Schema\Command	;
use Rentgen\Database\Table;

class GetTablesCommand extends Command
{
    private $schemaName = 'public';

    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf("SELECT table_name FROM information_schema.tables WHERE table_schema = '%s';", $this->schemaName);

        return $sql;
    }

    public function execute()
    {
        $this->preExecute();
        $result = $this->connection->query($this->getSql());
        $this->postExecute();

        $tables = array();
        foreach ($result as $row) {
            $tables[] = new Table($row['table_name']);
        }

        return $tables;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
    }
}
