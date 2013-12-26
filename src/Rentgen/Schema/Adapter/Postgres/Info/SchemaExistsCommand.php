<?php
namespace Rentgen\Schema\Adapter\Postgres\Info;

use Rentgen\Schema\Command;

class SchemaExistsCommand extends Command
{
    private $schemaName;

    /**
      * Sets a schema name.
      * 
      * @param string $schemaName Schema name.
      * 
      * @return SchemaExistsCommand
      */
    public function setName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf("SELECT count(schema_name) FROM information_schema.schemata WHERE schema_name = '%s';", $this->schemaName);

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
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
}
