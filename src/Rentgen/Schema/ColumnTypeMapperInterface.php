<?php

namespace Rentgen\Schema;

interface ColumnTypeMapperInterface
{
    public function getNative($type);

    public function getCommon($nativeType);
}
