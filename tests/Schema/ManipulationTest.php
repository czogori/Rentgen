<?php

namespace Rentgen\Tests\Schema;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rentgen\Rentgen;
use Rentgen\Database\Table;
use Rentgen\DependencyInjection\RentgenExtension;
use Rentgen\Schema\Manipulation;
use Rentgen\Tests\TestCase;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ManipulationTest extends TestCase
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
        $this->manipulation->create(new Table('foo'));
        $this->assertTrue($this->tableExists('foo'));
    }

    public function testDropTable()
    {
        $this->createTable('foo');

        $this->manipulation->drop(new Table('foo'));
        $this->assertFalse($this->tableExists('foo'));
    }

    public function testExecute()
    {
        $this->manipulation->execute('CREATE TABLE foo()');
        $this->assertTrue($this->tableExists('foo'));

        $this->manipulation->execute('DROP TABLE foo');
        $this->assertFalse($this->tableExists('foo'));
    }

    public function testClearDatabase()
    {
        $this->createTable('foo');
        $this->createTable('bar');
        $this->createSchema('moo');

        $this->assertEquals(2, count($this->getTables('public')));
        $this->assertTrue($this->schemaExists('public'));
        $this->assertTrue($this->schemaExists('moo'));

        $this->manipulation->clearDatabase();

        $this->assertEquals(0, count($this->getTables('public')));
        $this->assertTrue($this->schemaExists('public'));
        $this->assertFalse($this->schemaExists('moo'));
    }
}
