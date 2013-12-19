<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;

class DropSchemaCommand extends Command
{
    private $schemaName;

    /**
     * Sets a schema name.
     * 
     * @param string $schemaName The schema name.
     *
     * @return DropSchemaCommand
     */
    public function setName($schemaName)
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        return sprintf('DROP SCHEMA %s;', $this->schemaName);
    }
}
