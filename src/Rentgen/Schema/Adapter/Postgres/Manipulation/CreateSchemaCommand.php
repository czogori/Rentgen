<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Database\Schema;
use Rentgen\Schema\Command;

class CreateSchemaCommand extends Command
{
    private $schema;

    /**
     * Sets a schema.
     * 
     * @param Schema $schema The schema instance.
     * 
     * @return CreateSchemaCommand
     */
    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        return sprintf('CREATE SCHEMA %s;', $this->schema->getName());
    }
}
