<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class BooleanColumn extends Column
{    
    public function getDefault()
    {
        return null !== $this->default 
            ? filter_var($this->default, FILTER_VALIDATE_BOOLEAN)
            : null;
    }

    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return 'boolean';
    }    
}
