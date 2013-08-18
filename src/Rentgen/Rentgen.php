<?php

namespace Rentgen;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;



use Rentgen\Schema\Info;
use Rentgen\Schema\Manipulation;

class Rentgen
{
    private $container;

    public function __construct()
    {
        $container = new ContainerBuilder();
     

        $extension = new RentgenExtension();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());
        $container->compile();

        $this->container = $container;
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function createManipulationInstance()
    {
        return $this->container->get('schema.manipulation');
    }

    public function createInfoInstance()
    {
        return $this->container->get('schema.info');
    }

    public function execute($sql)
    {
        $connection = $this->get('connection');
        return $connection->execute($sql);
    }

    public function query($sql)
    {
        $connection = $this->get('connection');              
        return $connection->query($sql);
    }
}
