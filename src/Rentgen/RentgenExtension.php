<?php

namespace Rentgen;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

use Rentgen\Schema\Factory;

class RentgenExtension implements ExtensionInterface
{    
    public function load(array $configs, ContainerBuilder $container)
    {       
        $configDirectories = array(__DIR__ . '/../../vendor/czogori/rentgen/src/Rentgen', __DIR__);
        $loader = new YamlFileLoader($container, new FileLocator($configDirectories));
        $loader->load('services.yml');        

        $definition = new Definition('Rentgen\Schema\Manipulation');
        $definition->setArguments(array($container));
        $container->setDefinition('schema.manipulation', $definition); 

        $definition = new Definition('Rentgen\Schema\Info');
        $definition->setArguments(array($container));
        $container->setDefinition('schema.info', $definition); 


        $this->connection = $container->getDefinition('connection');
        $this->eventDispatcher = $container->getDefinition('event_dispatcher');
        $this->adapter = $container->getParameter('adapter');
    
        $this->setDefinition('create_table', 'command.manipulation.create_table.class', $container);    
        $this->setDefinition('drop_table', 'command.manipulation.drop_table.class', $container);    
        $this->setDefinition('add_foreign_key', 'command.manipulation.add_foreign_key.class', $container);    
        $this->setDefinition('drop_constraint', 'command.manipulation.drop_constraint.class', $container);    

        $this->setDefinition('table_exists', 'command.info.table_exists.class', $container);    

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

    private function setDefinition($name, $classParam, $container)
    {        
        $className = $container->getParameter($classParam);

        $definition = new Definition($this->getClassName($className, $this->adapter));
        $definition->addMethodCall('setConnection', array($this->connection));
        $definition->addMethodCall('setEventDispatcher', array($this->eventDispatcher));
        $container->setDefinition($name, $definition);
    }
}