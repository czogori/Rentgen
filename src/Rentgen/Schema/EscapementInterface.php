<?php

namespace Rentgen\Schema;

interface EscapementInterface
{
	/**
	 * Escapes a databse objest.
	 *
	 * @param string $databaseObject A database object.
	 *
	 * @return string
	 */
    public function escape($databaseObject);
}
