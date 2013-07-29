<?php

namespace Rentgen;

class Factory
{
    private $adapter;
    private $command;

    public function setCommand($command)
    {
        $class =  'Rentgen\\Schema\\' . $this->adapter .'\\Manipulation\\' . $command;

        return new $class();
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
}
