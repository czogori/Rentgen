<?php

namespace Rentgen\Schema;

use Rentgen\Schema\EscapementInterface;

class Escapement implements EscapementInterface
{
    /**
    * {@inheritdoc}
    */
   public function escape($databaseObject)
   {
        return '"' . str_replace('.', '"."', $databaseObject)  . '"';
   }
}
