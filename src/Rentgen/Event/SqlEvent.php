<?php

namespace Rentgen\Event;

use Symfony\Component\EventDispatcher\Event;
use Rentgen\Database\Table;

class SqlEvent extends Event
{
    private $sql;

    public function __construct($sql)
    {
        $this->sql = $sql;
    }

    public function getSql()
    {
        return $this->sql;
    }
}
