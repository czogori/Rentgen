<?php

namespace Rentgen\Tests\Schema;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\DependencyInjection\RentgenExtension;
use Rentgen\Schema\Info;
use Rentgen\Schema\Manipulation;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class InfoTest extends TestCase
{
    private $info;

    public function setUp()
    {
        $this->clearDatabase();

        $container = new ContainerBuilder();
        $extension = new RentgenExtension();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());
        $container->compile();

        $this->manipulation = new Manipulation($container);
        $this->info = new Info($container);

        $this->manipulation->create(new Table('foo'));
        $this->manipulation->create(new Table('bar'));
    }

    public function testGetTable()
    {        
        $table = $this->info->getTable(new Table('foo'));
        
        $this->assertInstanceOf('Rentgen\Database\Table', $table);                
        $this->assertEquals('foo', $table->getName());        
    }


    public function testGetTables()
    {        
        $tables = $this->info->getTables();
        
        $this->assertCount(2, $tables);
        $this->assertInstanceOf('Rentgen\Database\Table', $tables[0]);    
        $this->assertInstanceOf('Rentgen\Database\Table', $tables[1]);            
    }

    public function testIsTableExists()
    {
        $table = $this->info->isTableExists(new Table('foo'));
                
        $this->assertTrue($this->info->isTableExists(new Table('foo')));
    }
}
