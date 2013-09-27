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
        $this->getConstraints($table);

        $columnCreator = new ColumnCreator();
        foreach ($columns as $column) {
            $columnType = $columnTypeMapper->getCommon($column['data_type']);
            $options = array();
            $options['not_null'] = $column['is_nullable'] === 'NO';
            $options['default'] = $column['column_default'];
            if ($columnType === 'string') {
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

    private function getConstraints(Table $table)
    {
        $sql = sprintf("SELECT tc.constraint_name,
            tc.constraint_type,
            tc.table_name,
            kcu.column_name,
            tc.is_deferrable,
            tc.initially_deferred,
            rc.match_option AS match_type,
            rc.update_rule AS on_update,
            rc.delete_rule AS on_delete,
            ccu.table_name AS references_table,
            ccu.column_name AS references_field
     FROM information_schema.table_constraints tc
LEFT JOIN information_schema.key_column_usage kcu
       ON tc.constraint_catalog = kcu.constraint_catalog
      AND tc.constraint_schema = kcu.constraint_schema
      AND tc.constraint_name = kcu.constraint_name
LEFT JOIN information_schema.referential_constraints rc
       ON tc.constraint_catalog = rc.constraint_catalog
      AND tc.constraint_schema = rc.constraint_schema
      AND tc.constraint_name = rc.constraint_name
LEFT JOIN information_schema.constraint_column_usage ccu
       ON rc.unique_constraint_catalog = ccu.constraint_catalog
      AND rc.unique_constraint_schema = ccu.constraint_schema
      AND rc.unique_constraint_name = ccu.constraint_name
    WHERE tc.table_name = '%s' AND tc.constraint_type IN ('PRIMARY KEY', 'FOREIGN KEY')", $this->tableName);

        $constraints = $this->connection->query($sql);

        foreach ($constraints as $constraint) {
            print_r($constraint);die;
        }
    }
}
