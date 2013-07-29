<?php
namespace Rentgen\Adapter\Postgres\Command;

use Rentgen\Database\Table;

class CreateTableCommand extends Command
{
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    protected function sql()
    {
        $schema = $this->table->getSchema() == '' ? '' : $this->table->getSchema().'.';
        $sql = sprintf('CREATE TABLE %s%s(%s);'
            , $schema
            , $this->table->getName()
            , $this->columns());

        return $sql;
    }

    private function columns()
    {
        $sql = '';
        foreach ($this->table->columns as $column) {
            $sql .= sprintf('%s %s,', $column->getName(), $column->getType());
        }
        $sql = trim($sql, ',');

        return $sql;
    }
}
