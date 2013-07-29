<?php

namespace Rentgen\Event;

use Symfony\Component\EventDispatcher\Event;
use Rentgen\Database\Table;

class TableEvent extends Event
{
    private $table;
    private $sql;

    public function __construct(Table $table, $sql)
    {
        $this->table = $table;
        $this->sql = $sql;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getSql()
    {
        return $this->sql;
    }
}
