<?php

namespace Rentgen\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use Rentgen\Schema\Factory;

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

        $definition = new Definition('Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('event_dispatcher', $definition);

        $definition = new Definition('Rentgen\Schema\Manipulation');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('rentgen.schema.manipulation', $definition);

        $definition = new Definition('Rentgen\Schema\Info');
        $definition->setArguments(array(new Reference('service_container')));
        $container->setDefinition('rentgen.schema.info', $definition);

        if (!isset($connectionConfig)) {
            $fileLocator = new FileLocator(getcwd());
            try {
                $configFile = $fileLocator->locate('rentgen.yml');
                $content = file_get_contents('rentgen.yml');
                $config = Yaml::parse($content);
                $connectionConfig = $config;
            } catch (\InvalidArgumentException $e) {
                $connectionConfig['environments']['dev'] = array(
                    'adapter' => 'pgsql',
                    'host' => 'localhost',
                    'database' => null,
                    'username' => 'postgres',
                    'password' => '',
                    'port' => 5432,
                );
            }
        }

        $definition = new Definition('Rentgen\Database\Connection\ConnectionConfig');
        $definition->setArguments(array($connectionConfig['environments']));
        $container->setDefinition('connection_config', $definition);

        $definition = new Definition('Rentgen\Database\Connection\Connection');
        $definition->setArguments(array(
            new Reference('connection_config'),
            new Reference('event_dispatcher')
        ));
        $container->setDefinition('connection', $definition);

        $this->connection = $container->getDefinition('connection');
        $this->eventDispatcher = $container->getDefinition('event_dispatcher');

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

        $definition = new Definition($container->getParameter('rentgen.command.manipulation.clear_database.class'),
            array(new Reference('rentgen.get_schemas')));
        $definition->addMethodCall('setConnection', array(new Reference('connection')));
        $definition->addMethodCall('setEventDispatcher', array(new Reference('event_dispatcher')));
        $container->setDefinition('rentgen.clear_database', $definition);
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

    private function setDefinition($name, $classParam, $container)
    {
        $className = $container->getParameter($classParam);

        $definition = new Definition($className);
        $definition->addMethodCall('setConnection', array(new Reference('connection')));
        $definition->addMethodCall('setEventDispatcher', array(new Reference('event_dispatcher')));
        $container->setDefinition($name, $definition);
    }

    private function defineParameters(ContainerBuilder $container)
    {
        $container->setParameter('rentgen.command.factory.class', 'Rentgen\Schema\Factory');
        $container->setParameter('rentgen.command.manipulation.create_table.class', 'Rentgen\Schema\Manipulation\CreateTableCommand');
        $container->setParameter('rentgen.command.manipulation.drop_table.class', 'Rentgen\Schema\Manipulation\DropTableCommand');
        $container->setParameter('rentgen.command.manipulation.add_column.class', 'Rentgen\Schema\Manipulation\AddColumnCommand');
        $container->setParameter('rentgen.command.manipulation.drop_column.class', 'Rentgen\Schema\Manipulation\DropColumnCommand');
        $container->setParameter('rentgen.command.manipulation.add_constraint.class', 'Rentgen\Schema\Manipulation\AddConstraintCommand');
        $container->setParameter('rentgen.command.manipulation.drop_constraint.class', 'Rentgen\Schema\Manipulation\DropConstraintCommand');
        $container->setParameter('rentgen.command.manipulation.create_index.class', 'Rentgen\Schema\Manipulation\CreateIndexCommand');
        $container->setParameter('rentgen.command.manipulation.drop_index.class', 'Rentgen\Schema\Manipulation\DropIndexCommand');
        $container->setParameter('rentgen.command.manipulation.create_schema.class', 'Rentgen\Schema\Manipulation\CreateSchemaCommand');
        $container->setParameter('rentgen.command.manipulation.drop_schema.class', 'Rentgen\Schema\Manipulation\DropSchemaCommand');
        $container->setParameter('rentgen.command.manipulation.clear_database.class', 'Rentgen\Schema\Manipulation\ClearDatabaseCommand');
        $container->setParameter('rentgen.command.info.table_exists.class', 'Rentgen\Schema\Info\TableExistsCommand');
        $container->setParameter('rentgen.command.info.get_table.class', 'Rentgen\Schema\Info\GetTableCommand');
        $container->setParameter('rentgen.command.info.get_tables.class', 'Rentgen\Schema\Info\GetTablesCommand');
        $container->setParameter('rentgen.command.info.get_schemas.class', 'Rentgen\Schema\Info\GetSchemasCommand');
        $container->setParameter('rentgen.command.info.schema_exists.class', 'Rentgen\Schema\Info\SchemaExistsCommand');
    }

    private function isConnectionConfig($config)
    {
        return isset($config['username'])
            && isset($config['password'])
            && isset($config['dsn']);
    }
}
