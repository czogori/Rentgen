<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Database\Index;
use Rentgen\Schema\Command;

class DropIndexCommand extends Command
{
    private $index;

    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('DROP INDEX %s.%s;'
            , $this->index->getTable()->getSchema()->getName()
            , $this->index->getName());

        return $sql;
    }
}
