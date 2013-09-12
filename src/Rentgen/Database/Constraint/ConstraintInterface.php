<?php

namespace Rentgen\Database\Constraint;

interface ConstraintInterface
{
	public function getName();
	public function getTable();
}