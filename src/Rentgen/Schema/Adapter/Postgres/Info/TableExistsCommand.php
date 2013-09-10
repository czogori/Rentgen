<?php
namespace Rentgen\Schema\Adapter\Postgres\Info;

use Rentgen\Schema\Command;
use Rentgen\Database\Table;

class TableExistsCommand extends Command
{
    private $table;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getSql()
    {
        $schema = $this->table->getSchema() == '' ? 'public' : $this->table->getSchema();
        $sql = sprintf(
            "SELECT count(table_name) FROM information_schema.tables WHERE table_schema = '%s' AND table_name = '%s';"
            , $schema
            , $this->table->getName());

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

        return (Boolean) $count;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
    }
}
