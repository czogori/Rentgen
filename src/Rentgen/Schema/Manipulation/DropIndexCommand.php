<?php
namespace Rentgen\Schema\Manipulation;

use Rentgen\Database\Index;
use Rentgen\Schema\Command;

class DropIndexCommand extends Command
{
    private $index;

    /**
     * Sets a index
     *
     * @param Index $index The index instance.
     *
     * @return DropColumnCommand
     */
    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('DROP INDEX %s.%s;'
            , $this->index->getTable()->getSchema()->getName()
            , $this->index->getName());

        return $sql;
    }
}
