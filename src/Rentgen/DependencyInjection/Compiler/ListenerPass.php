<?php

namespace Rentgen\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ListenerPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $dispatcher = $container->get('rentgen.event_dispatcher');
        $listener = $container->get('rentgen.event_listener');

        $taggedServices = $container->findTaggedServiceIds('table');
        foreach ($taggedServices as $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $dispatcher->addListener($attributes['event'], array($listener, $attributes['method']));
            }
        }
    }
}
