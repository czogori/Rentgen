<?php
namespace Rentgen\Schema\Manipulation;

use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Schema\Command;

class DropConstraintCommand extends Command
{
    private $constraint;

    /**
     * Set a constraint.
     *
     * @param ConstraintInterface $constraint The constraint instance.
     *
     * @return DropConstraintCommand
     */
    public function setConstraint(ConstraintInterface $constraint)
    {
        $this->constraint = $constraint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $sql = sprintf('ALTER TABLE %s DROP CONSTRAINT %s;'
            , $this->constraint->getTable()->getQualifiedName()
            , $this->constraint->getName()
        );

        return $sql;
    }
}
