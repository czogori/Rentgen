<?php

namespace Rentgen\Database;

interface ConstraintInterface
{
	public function getName();
	public function getTable();
}