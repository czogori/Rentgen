<?php

namespace Rentgen\Tests\Schema;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rentgen\Rentgen;
use Rentgen\RentgenExtension;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;
use Rentgen\Tests\TestHelpers;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ManipulationTest extends TestHelpers
{
    private $manipulation;

    public function setUp()
    {
        $this->clearDatabase();

        $container = new ContainerBuilder();
        $extension = new RentgenExtension();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());
        $container->compile();

         $this->manipulation = new Manipulation($container);
    }

    public function testCreateTable()
    {
         $this->manipulation->createTable(new Table('foo'));
        $this->assertTrue($this->tableExists('foo'));
    }

    public function testDropTable()
    {
         $this->manipulation->createTable(new Table('foo'));
        $this->assertTrue($this->tableExists('foo'));

        $this->manipulation->dropTable(new Table('foo'));
        $this->assertFalse($this->tableExists('foo'));
    }

    public function testExecute()
    {
        $this->manipulation->execute('CREATE TABLE foo()');
        $this->assertTrue($this->tableExists('foo'));

        $this->manipulation->execute('DROP TABLE foo');
        $this->assertFalse($this->tableExists('foo'));
    }
}
