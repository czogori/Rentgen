<?php

namespace Rentgen;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ListenerPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $dispatcher = $container->get('event_dispatcher');
        $listener = $container->get('event_listener');

        $taggedServices = $container->findTaggedServiceIds('table');
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $dispatcher->addListener($attributes['event'], array($listener, $attributes['method']));
            }
        }
    }
}