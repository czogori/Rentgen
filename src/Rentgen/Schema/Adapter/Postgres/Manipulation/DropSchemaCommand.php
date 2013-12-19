<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Database\Schema;
use Rentgen\Schema\Command;

class DropSchemaCommand extends Command
{
    private $schema;

    /**
     * Sets a schema.
     * 
     * @param Schema $schema The schema instance.
     * 
     * @return DropSchemaCommand
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
        return sprintf('DROP SCHEMA %s;', $this->schema->getName());
    }
}
