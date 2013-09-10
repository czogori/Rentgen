<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class StringColumn extends Column
{

    /**
     * Get column type name.
     * 
     * @return string Column type name.
     */
    public function getType()
    {
        return 'string';
    }    

    /**
     * Get default value of column.
     * 
     * @return mixed Default value of column.
     */
    public function getDefault()
    {
        return null === $this->default ?: (string) $this->default;
    }    

    public function getLimit()
    {
        
    }
}
