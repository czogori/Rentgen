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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('services.yml');

        $dispatcher = $container->get('event_dispatcher');
        $listener = $container->get('event_listener');

        $taggedServices = $container->findTaggedServiceIds('table');
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $dispatcher->addListener($attributes['event'], array($listener, $attributes['method']));
            }
        }

        $definition = new Definition('Rentgen\Schema\Manipulation');
        $definition->setArguments(array($this));
        $container->setDefinition('schema.manipulation', $definition);        

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
}
