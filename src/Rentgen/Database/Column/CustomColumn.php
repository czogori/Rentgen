<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class CustomColumn extends Column
{   
    private $type;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $type, array $options = array())
    {
        parent::__construct($name, $options);
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }
}
