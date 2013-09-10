<?php
namespace Rentgen\Schema\Postgres\Info;

use Rentgen\Schema\Command;
use Rentgen\Schema\Postgres\ColumnTypeMapper;
use Rentgen\Database\Table;
use Rentgen\Database\Column\ColumnCreator;

class GetTableCommand extends Command
{
    private $tableName;

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getSql()
    {
        $sql = sprintf("SELECT column_name, data_type, is_identity, is_nullable,
            column_default, character_maximum_length, numeric_precision, numeric_scale
            FROM information_schema.columns
            WHERE table_name ='%s';", $this->tableName);

        return $sql;
    }

    public function execute()
    {
        $columnTypeMapper = new ColumnTypeMapper();

        $this->preExecute();
        $columns = $this->connection->query($this->getSql());
        $this->postExecute();

        $table = new Table($this->tableName);

        $columnCreator = new ColumnCreator();
        foreach ($columns as $column) {
            $columnType = $columnTypeMapper->getCommon($column['data_type']);
            //$column = new Column($column['column_name'], $columnType);
            $column = $columnCreator->create($column['column_name'], $columnType);
            $table->addColumn($column);
        }

        return $table;
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
    }
}
