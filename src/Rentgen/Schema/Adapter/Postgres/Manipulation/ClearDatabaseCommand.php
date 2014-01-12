<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Schema\Adapter\Postgres\Info\GetSchemasCommand;

class ClearDatabaseCommand extends Command
{
    private $getSchemasCommand;

    /**
     * Constructor.
     *
     * @param GetSchemasCommand $getSchemasCommand
     */
    public function __construct(GetSchemasCommand $getSchemasCommand)
    {
        $this->getSchemasCommand = $getSchemasCommand;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = '';
        $schemas = $this->getSchemasCommand->execute();
        foreach ($schemas as $schema) {
            $sql .= sprintf('DROP SCHEMA "%s" CASCADE;', $schema->getName());
        }
        $sql .= 'CREATE SCHEMA public;';

        return $sql;
    }
}
