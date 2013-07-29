<?php

namespace Rentgen;

use Rentgen\Event\TableEvent;

class Listener
{
    public function onCreateTable(TableEvent $event)
    {
        echo 'Table ' . $event->getTable()->getName() . ' was created.';
        echo "\n";
        echo '[sql] ' . $event->getSql();
        echo "\n\n";
    }

     public function onDropTable(TableEvent $event)
    {
        echo 'Table ' . $event->getTable()->getName() . ' was dropped.';
        echo "\n";
        echo '[sql] ' . $event->getSql();
        echo "\n\n";
    }
}
