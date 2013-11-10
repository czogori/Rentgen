<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Index;

class CreateIndexCommand extends Command
{
    private $index;

    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('CREATE INDEX %s ON %s (%s);'
            , $this->index->getName()
            , $this->index->getTable()->getQualifiedName()
            , implode(',', $this->index->getColumns()));

        return $sql;
    }
}
