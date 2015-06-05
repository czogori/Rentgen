<?php

namespace Rentgen\Schema;

use Rentgen\Schema\ColumnTypeMapperInterface;

class ColumnTypeMapper implements ColumnTypeMapperInterface
{
    private $commonMap = array(
        'string' => 'character varying',
        'text' => 'text',
        'integer' => 'integer',
        'biginteger' => 'bigint',
        'smallinteger' => 'smallint',
        'float' => 'real',
        'decimal' => 'decimal',
        'boolean' => 'boolean',
        'datetime' => 'timestamp',
        'date' => 'date',
        'time' => 'time',
        'binary' => 'bytea'
        );

    private $nativeMap = array(
        'character varying' => 'string',
        'text' => 'text',
        'integer' => 'integer',
        'bigint' => 'biginteger',
        'smallint' => 'smallinteger',
        'decimal' => 'decimal',
        'real' => 'float',
        'boolean' => 'boolean',
        'timestamp' => 'datetime',
        'date' => 'date',
        'time' => 'time',
        'bytea' => 'binary',

        );

    public function getNative($type)
    {
        $mappedType = $this->commonMap[$type];

        return $mappedType;
    }

    public function getCommon($nativeType)
    {
        $mappedType = $this->nativeMap[$nativeType];

        return $mappedType;
    }
}
