<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class BinaryColumn extends Column
{
    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return 'binary';
    }    
}
