<?php

namespace Rentgen\Schema\Postgres;

use Rentgen\Schema\ColumnTypeMapperInterface;

class ColumnTypeMapper implements ColumnTypeMapperInterface
{
    private $commonMap = array(
        'string' => 'character varying',
        'integer' => 'integer'
        );

    private $nativeMap = array(
        'character varying' => 'string',
        'integer' => 'integer'
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
