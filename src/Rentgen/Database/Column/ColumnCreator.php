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
			case 'text':
				return new TextColumn($name, $options);			
			case 'integer':
				return new IntegerColumn($name, $options);			
			case 'biginteger':
				return new BigIntegerColumn($name, $options);			
			case 'smallinteger':
				return new SmallIntegerColumn($name, $options);			
			case 'float':
				return new FloatColumn($name, $options);			
			case 'decimal':
				return new DecimalColumn($name, $options);			
			case 'boolean':
				return new BooleanColumn($name, $options);			
			case 'date':
				return new DateColumn($name, $options);			
			case 'time':
				return new TimeColumn($name, $options);			
			case 'datetime':
				return new DateTimeColumn($name, $options);			
			case 'binary':
				return new BinaryColumn($name, $options);			
			default:
				throw new NotSupportedException(sprintf('Type %s is not suppoted.', $type));				
		}
	}
}