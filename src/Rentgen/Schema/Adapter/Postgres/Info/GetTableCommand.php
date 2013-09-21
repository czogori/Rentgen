<?php
namespace Rentgen\Schema\Adapter\Postgres\Info;

use Rentgen\Schema\Command;
use Rentgen\Schema\Adapter\Postgres\ColumnTypeMapper;
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
            $options = array();
            $options['not_null'] = $column['is_nullable'] === 'NO';
            $options['default'] = $column['column_default'];            
            if($columnType === 'string') {
                preg_match("/'(.*)'::character varying/", $column['column_default'], $matches);                
                $options['default'] = isset($matches[1]) ? $matches[1] : '';       
                $options['limit'] = $column['character_maximum_length'];
            }
            $column = $columnCreator->create($column['column_name'], $columnType, $options);
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
