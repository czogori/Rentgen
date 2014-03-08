<?php

namespace Rentgen;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rentgen\DependencyInjection\RentgenExtension;
use Rentgen\Schema\Info;
use Rentgen\Schema\Manipulation;

class Rentgen
{
    private $container;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $container = new ContainerBuilder();

        $extension = new RentgenExtension();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());
        $container->compile();

        $this->container = $container;
    }

    /**
     * Get the container.
     *
     * @return Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get a service.
     *
     * @param string $service A service name.
     *
     * @return mixed
     */
    public function get($service)
    {
        return $this->container->get($service);
    }

    /**
     * Create the manipulation instance.
     *
     * @return Rentgen\Schema\Manipulation
     */
    public function createManipulationInstance()
    {
        return $this->container->get('rentgen.schema.manipulation');
    }

    /**
     * Create the info instance.
     *
     * @return Rentgen\Schema\Info
     */
    public function createInfoInstance()
    {
        return $this->container->get('rentgen.schema.info');
    }

    /**
     * Execute sql query.
     *
     * @param string $sql A sql query.
     *
     * @return integer
     */
    public function execute($sql)
    {
        $connection = $this->get('connection');

        return $connection->execute($sql);
    }

     /**
     * Execute sql query with select intention.
     *
     * @param string $sql A sql query.
     *
     * @return array
     */
    public function query($sql)
    {
        $connection = $this->get('connection');

        return $connection->query($sql);
    }
}
