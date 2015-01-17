<?php
namespace Rentgen\Schema\Info;

use Rentgen\Database\Schema;
use Rentgen\Schema\Command;

class GetSchemasCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = 'SELECT schema_name
                FROM information_schema.schemata
                WHERE schema_name <> \'information_schema\'
                AND schema_name !~ \'^pg_\'';

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

        $schemas = array();
        foreach ($result as $row) {
            $schemas[] = new Schema($row['schema_name']);
        }

        return $schemas;
    }
}
