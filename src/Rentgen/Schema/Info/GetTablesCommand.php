<?php
namespace Rentgen\Schema\Info;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;
use Rentgen\Database\Schema;

class GetTablesCommand extends Command
{
    private $schemaName;

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
        $schemaCondition = null === $this->schemaName
            ? ''
            : sprintf(" AND table_schema = '%s'", $this->schemaName);
        $sql = sprintf("SELECT table_name, table_schema FROM information_schema.tables WHERE table_schema <> 'information_schema'
            AND table_schema <> 'pg_catalog'%s;", $schemaCondition);

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
            $tables[] = new Table($row['table_name'], new Schema($row['table_schema']));
        }

        return $tables;
    }
}
