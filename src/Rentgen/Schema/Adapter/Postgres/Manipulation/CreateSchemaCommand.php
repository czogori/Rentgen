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

    public function getSql()
    {
        return sprintf('CREATE SCHEMA %s;', $this->schemaName);
    }

    protected function preExecute()
    {
    }

    protected function postExecute()
    {
    }
}
