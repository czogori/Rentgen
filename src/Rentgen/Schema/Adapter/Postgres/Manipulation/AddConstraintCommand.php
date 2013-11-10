<?php
namespace Rentgen\Schema\Adapter\Postgres\Manipulation;

use Rentgen\Schema\Command;
use Rentgen\Database\Constraint\ConstraintInterface;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Table;

class AddConstraintCommand extends Command
{
    private $constraint;

    /**
     * Set a constraint.
     *
     * @param ConstraintInterface $constraint A constraint instance.
     *
     * @return AddConstraintCommand
     */
    public function setConstraint(ConstraintInterface $constraint)
    {
        $this->constraint = $constraint;

        return $this;
    }

    /**
     * Get sql query.
     *
     * @return string Sql query.
     */
    public function getSql()
    {
        if ($this->constraint instanceof ForeignKey) {
            return sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE;'
                , $this->constraint->getTable()->getQualifiedName()
                , $this->constraint->getName()
                , implode(',', $this->constraint->getColumns())
                , $this->constraint->getReferencedTable()->getQualifiedName()
                , implode(',', $this->constraint->getReferencedColumns())
            );
        }
        if ($this->constraint instanceof Unique) {
            return sprintf('ALTER TABLE %s ADD CONSTRAINT %s UNIQUE (%s);'
                , $this->constraint->getTable()->getQualifiedName()
                , $this->constraint->getName()
                , implode(',', $this->constraint->getColumns())
            );
        }
    }
}
