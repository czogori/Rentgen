<?php

namespace Rentgen\Database\Connection;

interface ConnectionConfigInterface
{
    public function getUsername();

    public function getPassword();

    public function getDsn();
}
