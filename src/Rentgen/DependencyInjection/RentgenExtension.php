<?php

namespace Rentgen\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

use Rentgen\Schema\Factory;
use Rentgen\DependencyInjection\Compiler\ListenerPass;

class RentgenExtension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        foreach ($configs as $config) {
            if ($this->isConnectionConfig($config)) {
                $connectionConfig = $config;
            }
        }
        if ($container->hasParameter('connection_config')) {
            $connectionConfig = $container->getParameter('connection_config');
        }

        $this->defineParameters($container);

        $definition = new Definition($container->getParameter('rentgen.event_dispatcher.class'));
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('rentgen.event_dispatcher', $definition);

        $definition = new Definition($container->getParameter('rentgen.event_listener.class'));
        $container->setDefinition('rentgen.event_listener', $definition);

        $definition = new Definition('Rentgen\Schema\Manipulation');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('rentgen.schema.manipulation', $definition);

        $definition = new Definition('Rentgen\Schema\Info');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('rentgen.schema.info', $definition);

        if (!isset($connectionConfig)) {
            $fileLocator = new FileLocator(getcwd());
            $configFile = $fileLocator->locate('rentgen.yml');
            $config = Yaml::parse($configFile);

            $connectionConfig = $config;
        }

        $definition = new Definition('Rentgen\Database\Connection\ConnectionConfig');
        $definition->setArguments(array($connectionConfig['environments']));
        $container->setDefinition('connection_config', $definition);

        $definition = new Definition('Rentgen\Database\Connection\Connection');
        $definition->setArguments(array(new Reference('connection_config')));
        $container->setDefinition('connection', $definition);

        $this->connection = $container->getDefinition('connection');
        $this->eventDispatcher = $container->getDefinition('rentgen.event_dispatcher');
        $this->adapter = $this->parseAdapter($connectionConfig['environments'][$connectionConfig['current_environment']]['adapter']);

        $this->setDefinition('rentgen.create_table', 'rentgen.command.manipulation.create_table.class', $container);
        $this->setDefinition('rentgen.drop_table', 'rentgen.command.manipulation.drop_table.class', $container);
        $this->setDefinition('rentgen.add_column', 'rentgen.command.manipulation.add_column.class', $container);
        $this->setDefinition('rentgen.drop_column', 'rentgen.command.manipulation.drop_column.class', $container);
        $this->setDefinition('rentgen.add_constraint', 'rentgen.command.manipulation.add_constraint.class', $container);
        $this->setDefinition('rentgen.drop_constraint', 'rentgen.command.manipulation.drop_constraint.class', $container);
        $this->setDefinition('rentgen.create_index', 'rentgen.command.manipulation.create_index.class', $container);
        $this->setDefinition('rentgen.create_schema', 'rentgen.command.manipulation.create_schema.class', $container);
        $this->setDefinition('rentgen.drop_schema', 'rentgen.command.manipulation.drop_schema.class', $container);
        $this->setDefinition('rentgen.drop_index', 'rentgen.command.manipulation.drop_index.class', $container);

        $this->setDefinition('rentgen.table_exists', 'rentgen.command.info.table_exists.class', $container);
        $this->setDefinition('rentgen.get_table', 'rentgen.command.info.get_table.class', $container);
        $this->setDefinition('rentgen.get_tables', 'rentgen.command.info.get_tables.class', $container);
        $this->setDefinition('rentgen.get_schemas', 'rentgen.command.info.get_schemas.class', $container);
        $this->setDefinition('rentgen.schema_exists', 'rentgen.command.info.schema_exists.class', $container);

        $definition = new Definition($this->getClassName($container->getParameter('rentgen.command.manipulation.clear_database.class'), $this->adapter),
            array(new Reference('rentgen.get_schemas')));
        $definition->addMethodCall('setConnection', array(new Reference('connection')));
        $definition->addMethodCall('setEventDispatcher', array(new Reference('rentgen.event_dispatcher')));
        $container->setDefinition('rentgen.clear_database', $definition);

        $definition = new Definition('Rentgen\Event\TableEvent');
        $definition->addTag('table', array('event' => 'table.create',   'method' => 'onCreateTable'));
        $definition->addTag('table', array('event' => 'table.drop',     'method' => 'onDropTable'));
        $container->setDefinition('rentgen.event_table', $definition);

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
        $definition->addMethodCall('setEventDispatcher', array(new Reference('rentgen.event_dispatcher')));
        $container->setDefinition($name, $definition);
    }

    private function defineParameters(ContainerBuilder $container)
    {
        $container->setParameter('rentgen.command.factory.class', 'Rentgen\Schema\Factory');
        $container->setParameter('rentgen.command.manipulation.create_table.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\CreateTableCommand');
        $container->setParameter('rentgen.command.manipulation.drop_table.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropTableCommand');
        $container->setParameter('rentgen.command.manipulation.add_column.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\AddColumnCommand');
        $container->setParameter('rentgen.command.manipulation.drop_column.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropColumnCommand');
        $container->setParameter('rentgen.command.manipulation.add_constraint.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\AddConstraintCommand');
        $container->setParameter('rentgen.command.manipulation.drop_constraint.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropConstraintCommand');
        $container->setParameter('rentgen.command.manipulation.create_index.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\CreateIndexCommand');
        $container->setParameter('rentgen.command.manipulation.drop_index.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropIndexCommand');
        $container->setParameter('rentgen.command.manipulation.create_schema.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\CreateSchemaCommand');
        $container->setParameter('rentgen.command.manipulation.drop_schema.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\DropSchemaCommand');
        $container->setParameter('rentgen.command.manipulation.clear_database.class', 'Rentgen\Schema\Adapter\@@adapter@@\Manipulation\ClearDatabaseCommand');
        $container->setParameter('rentgen.command.info.table_exists.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\TableExistsCommand');
        $container->setParameter('rentgen.command.info.get_table.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\GetTableCommand');
        $container->setParameter('rentgen.command.info.get_tables.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\GetTablesCommand');
        $container->setParameter('rentgen.command.info.get_schemas.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\GetSchemasCommand');
        $container->setParameter('rentgen.command.info.schema_exists.class', 'Rentgen\Schema\Adapter\@@adapter@@\Info\SchemaExistsCommand');
        $container->setParameter('rentgen.event_dispatcher.class', 'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher');
        $container->setParameter('rentgen.event_listener.class', 'Rentgen\EventListener\LoggingListener');
    }

    private function isConnectionConfig($config)
    {
        return isset($config['username'])
            && isset($config['password'])
            && isset($config['dsn']);
    }
}
