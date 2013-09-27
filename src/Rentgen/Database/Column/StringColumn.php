<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class StringColumn extends Column implements LimitableInterface
{
    private $limit;

    public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);

        if (array_key_exists('limit', $options)) {
            $this->limit = $options['limit'];
        }
    }
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
        return $this->default;
    }

    /**
     * Get characters limit.
     *
     * @return integer Number of characters.
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
