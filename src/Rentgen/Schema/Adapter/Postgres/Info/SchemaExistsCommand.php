<?php
namespace Rentgen\Schema\Adapter\Postgres\Info;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;

class SchemaExistsCommand extends Command
{
    private $schemaName;

    public function setName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    public function getSql()
    {       
        $sql = sprintf("SELECT count(schema_name) FROM information_schema.schemata WHERE schema_name = '%s';", $this->schemaName);            
        return $sql;
    }

    public function execute()
    {
        $this->preExecute();
        $result = $this->connection->query($this->getSql());
        foreach ($result as $row) {
            $count = $row['count'];
        }
        $this->postExecute();

        return (bool) $count;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
    }
}
