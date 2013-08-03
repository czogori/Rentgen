<?php

namespace Rentgen;

class Factory
{
    private $adapter;   

    /**
     * Set fully qualified class name
     * 
     * @param string $class Fully qualified class name.
     */
    public function setClass($class)
    {    
        $className =  str_replace('@@adapter@@', $this->adapter, $class);        
        return new $className();
    }

    /**
     * Set database adapter
     * 
     * @param string $adapter Database adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
}
