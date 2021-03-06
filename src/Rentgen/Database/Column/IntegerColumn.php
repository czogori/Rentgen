<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class IntegerColumn extends Column
{
    /**
     * Get column type name.
     *
     * @return string Column type name.
     */
    public function getType()
    {
        return 'integer';
    }

    /**
     * Get default value of column.
     *
     * @return mixed Default value of column.
     */
    public function getDefault()
    {
        return $this->default;
    }
}
