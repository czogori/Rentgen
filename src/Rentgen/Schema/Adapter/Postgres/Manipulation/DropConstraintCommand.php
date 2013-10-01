<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Schema\Command;

class DropConstraintCommand extends Command
{
    private $constraint;

    public function setConstraint(ConstraintInterface $constraint)
    {
        $this->constraint = $constraint;

        return $this;
    }

    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s.%s DROP CONSTRAINT %s;'
            , $this->getSchema()
            , $this->constraint->getTable()->getName()
            , $this->constraint->getName()
        );

        return $sql;
    }

    private function getSchema()
    {
        $schemaName = $this->constraint->getTable()->getSchema();

        return empty($schemaName) ? 'public' : $schemaName;
    }
}
