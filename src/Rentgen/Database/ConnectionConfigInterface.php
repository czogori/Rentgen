<?php

namespace Rentgen\Database;

interface ConnectionConfigInterface
{
	public function getLogin();
    
    public function getPassword();
    
    public function getDsn();
}