<?php

namespace Rentgen\Database\Column;

use Rentgen\Database\Column;

class DecimalColumn extends Column
{
	private $precision;
	private $scale = 0;

	/**
     * {@inheritdoc}
     */
	public function __construct($name, array $options = array())
    {
        parent::__construct($name, $options);

        if (array_key_exists('precision', $options)) {
            $this->precision = $options['precision'];
	        if (array_key_exists('scale', $options)) {
	            $this->scale = $options['scale'];
	        }
        }
    }

    /**
     * Gets a number precision.
     *
     * @return integer
     */
	public function getPrecision()
	{
		return $this->precision;
	}

	/**
     * Gets a number scale.
     *
     * @return integer
     */
	public function getScale()
	{
		return $this->scale;
	}

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'decimal';
    }
}
