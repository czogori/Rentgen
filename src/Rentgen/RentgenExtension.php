<?php

namespace Rentgen;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use Rentgen\Schema\Factory;

class RentgenExtension implements ExtensionInterface
{
    public function __construct(array $connectionConfig = array())
    {
        $this->connectionConfig = $connectionConfig;
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $this->defineParameters($container);

        $definition = new Definition($container->getParameter('event_dispatcher.class'));
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('event_dispatcher', $definition);

        $definition = new Definition($container->getParameter('event_listener.class'));
        $container->setDefinition('event_listener', $definition);

        $definition = new Definition('Rentgen\Schema\Manipulation');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('schema.manipulation', $definition);

        $definition = new Definition('Rentgen\Schema\Info');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('schema.info', $definition);

        if (empty($this->connectionConfig)) {
            $fileLocator = new FileLocator(getcwd());
            $configFile = $fileLocator->locate('rentgen.yml');
            $config = Yaml::parse($configFile);

            $this->connectionConfig = $config['connection'];
        }

        $definition = new Definition('Rentgen\Database\Connection\ConnectionConfig');
        $definition->setArguments(array($this->connectionConfig));
        $container->setDefinition('connection_config', $definition);

        $definition = new Definition('Rentgen\Database\Connection\Connection');
        $definition->setArguments(array(new Reference('connection_config')));
        $container->setDefinition('connection', $definition);

        $this->connection = $container->getDefinition('connection');
        $this->eventDispatcher = $container->getDefinition('event_dispatcher');
        $this->adapter = $this->parseAdapter($this->connectionConfig['adapter']);

        $this->setDefinition('create_table', 'command.manipulation.create_table.class', $container);
        $this->setDefinition('drop_table', 'command.manipulation.drop_table.class', $container);
        $this->setDefinition('add_foreign_key', 'command.manipulation.add_foreign_key.class', $container);
        $this->setDefinition('drop_constraint', 'command.manipulation.drop_constraint.class', $container);
        $this->setDefinition('create_schema', 'command.manipulation.create_schema.class', $container);

        $this->setDefinition('table_exists', 'command.info.table_exists.class', $container);

        $definition = new Definition('Rentgen\Event\TableEvent');
        $definition->addTag('table', array('event' => 'table.create',   'method' => 'onCreateTable'));
        $definition->addTag('table', array('event' => 'table.drop',     'method' => 'onDropTable'));
        $container->setDefinition('event_table', $definition);

        $container->addCompilerPass(new ListenerPass);
    }

    public function getAlias()
    {
        return 'rentgen';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }

    private function getClassName($className, $adapter)
    {
        return  str_replace('@@adapter@@', $adapter, $className);
    }

    private function parseAdapter($adapter)
    {
        switch (strtolower($adapter)) {
            case 'pgsql':
            case 'postgres':
            case 'postgresql':
                return 'Postgres';
            default:
                return '';
        }
    }

    private function setDefinition($name, $classParam, $container)
    {
        $className = $container->getParameter($classParam);

        $definition = new Definition($this->getClassName($className, $this->adapter));
        $definition->addMethodCall('setConnection', array(new Reference('connection')));
        $definition->addMethodCall('setEventDispatcher', array(new Reference('event_dispatcher')));
        $container->setDefinition($name, $definition);
    }

    private function defineParameters(ContainerBuilder $container)
    {
        $container->setParameter('command.factory.class', 'Rentgen\Schema\Factory');
        $container->setParameter('command.manipulation.create_table.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\CreateTableCommand');
        $container->setParameter('command.manipulation.drop_table.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropTableCommand');
        $container->setParameter('command.manipulation.add_foreign_key.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\AddForeignKeyCommand');
        $container->setParameter('command.manipulation.drop_constraint.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropConstraintCommand');
        $container->setParameter('command.manipulation.create_schema.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\CreateSchemaCommand');
        $container->setParameter('command.info.table_exists.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\TableExistsCommand');
        $container->setParameter('event_dispatcher.class', 'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher');
        $container->setParameter('event_listener.class', 'Rentgen\Listener');
    }
}
