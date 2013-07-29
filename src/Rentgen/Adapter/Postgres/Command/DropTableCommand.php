<?php

namespace Rentgen\Adapter\Postgres\Command;

class DropTableCommand extends Command
{
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    protected function sql()
    {
        $sql = sprintf('DROP TABLE %s;', $this->table->getName());

        return $sql;
    }
}
