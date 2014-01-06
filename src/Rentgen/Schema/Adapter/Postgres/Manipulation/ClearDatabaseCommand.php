<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

class ClearDatabseCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('CREATE INDEX %s ON %s (%s);'
            , $this->index->getName()
            , $this->index->getTable()->getQualifiedName()
            , implode(',', $this->index->getColumns()));

        return $sql;
    }
}
