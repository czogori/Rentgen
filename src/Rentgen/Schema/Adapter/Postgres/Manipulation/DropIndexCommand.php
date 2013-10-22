<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Database\Index;
use Rentgen\Schema\Command;

class DropIndexCommand extends Command
{
    private $indexName;

    public function setIndexName($indexName)
    {
        $this->indexName = $indexName;
        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('DROP INDEX %s;', $this->indexName);
        return $sql;
    }
}
