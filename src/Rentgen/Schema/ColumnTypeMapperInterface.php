<?php

namespace Rentgen\Schema;

interface ColumnTypeMapperInterface
{
	function getNative($type);

	function getCommon($nativeType);
}