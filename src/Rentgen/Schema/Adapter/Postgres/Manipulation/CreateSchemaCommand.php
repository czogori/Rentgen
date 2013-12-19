<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;

class CreateSchemaCommand extends Command
{
    private $schemaName;

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
        return sprintf('CREATE SCHEMA %s;', $this->schemaName);
    }
}
