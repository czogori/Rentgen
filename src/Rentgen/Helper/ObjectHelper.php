<?php

namespace Rentgen\Helper;

class ObjectHelper
{
	/**
	 * Gets a class name without a namespace.
	 * 
	 * @param object $object An object.
	 * 
	 * @return string
	 */
	public static function getClassNameWithoutNamespace($object) 
    {
        $className = get_class($object);
        if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
            $className = $matches[1];
        }

        return $className;
    }
}