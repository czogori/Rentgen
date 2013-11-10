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
        $sql = sprintf('ALTER TABLE %s DROP CONSTRAINT %s;'
            , $this->constraint->getTable()->getQualifiedName()
            , $this->constraint->getName()
        );

        return $sql;
    }
}
