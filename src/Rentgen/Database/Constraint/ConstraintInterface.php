<?php

namespace Rentgen\Database\Constraint;

use Rentgen\Database\DatabaseObjectInterface;

interface ConstraintInterface extends DatabaseObjectInterface
{
    /**
     * Get constraint name.
     *
     * @return string Constraint name.
     */
    public function getName();

    /**
     * Get table instance.
     *
     * @return Table Table instance.
     */
    public function getTable();
}
