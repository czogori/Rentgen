<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class DecimalColumn extends Column
{
    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return 'decimal';
    }    
}
