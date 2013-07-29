<?php

namespace Rentgen\Event;

use Symfony\Component\EventDispatcher\Event;
use Rentgen\Database\Column;

class ColumnEvent extends Event
{
    private $column;
    private $sql;

    public function __construct(Column $column, $sql)
    {
        $this->column = $column;
        $this->sql = $sql;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getSql()
    {
        return $this->sql;
    }
}
