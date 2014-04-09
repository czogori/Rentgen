<?php

namespace Rentgen\Database\Connection;

interface ConnectionConfigInterface
{
	/**
	 * Get user name.
	 *
	 * @return string
	 */
    public function getUsername();

	/**
	 * Get password.
	 *
	 * @return string
	 */
    public function getPassword();

    /**
	 * Get DSN.
	 *
	 * @return string
	 */
    public function getDsn();
}
