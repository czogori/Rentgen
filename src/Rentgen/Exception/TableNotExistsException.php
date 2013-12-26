<?php

namespace Rentgen\Exception;

class TableNotExistsException extends \Exception
{
	private $tableName;

	/**
	 * Constructor.
	 * 
	 * @param string $tableName A table name.
	 */
	public function __construct($tableName)
	{
		$this->tableName = $tableName;
	}

	/**
	 * Gets a table name.
	 * 
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}
}
