<?php
namespace Rentgen\Schema\Adapter\Postgres\Info;

use Rentgen\Schema\Command	;
use Rentgen\Database\Table;

class GetTablesCommand extends Command
{
    private $schemaName = 'public';

     /**
      * Sets a schema name.
      * 
      * @param string $schemaName Schema name.
      * 
      * @return GetTablesCommand
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
        $sql = sprintf("SELECT table_name FROM information_schema.tables WHERE table_schema = '%s';", $this->schemaName);

        return $sql;
    }

     /**
     * {@inheritdoc}
     */
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
}
