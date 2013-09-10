<?php

namespace Rentgen\Database\Column;

use Rentgen\Exception\NotSupportedException;

class ColumnCreator
{
	public function create($name, $type, array $options = array())
	{
		switch ($type) {
			case 'string':
				return new StringColumn($name, $options);			
			case 'integer':
				return new IntegerColumn($name, $options);			
			case 'boolean':
				return new Booleanolumn($name, $options);			
			case 'string':
				return new StringColumn($name, $options);			
			default:
				throw new NotSupportedException(sprintf('Type %s is not suppoted.', $type));				
		}
	}
}