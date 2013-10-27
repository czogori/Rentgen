<?php

namespace Rentgen\Database\Constraint;

interface ConstraintInterface
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
