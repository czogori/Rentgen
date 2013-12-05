<?php

namespace Rentgen\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Rentgen\Rentgen;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class RentgenTest extends \PHPUnit_Framework_TestCase
{    
    public function setUp()
    {
        $this->rentgen = new Rentgen();        
    }

    public function testCreateManipulationInstance()
    {
        $this->assertInstanceOf('Rentgen\Schema\Manipulation', $this->rentgen->createManipulationInstance());
    }

    public function testCreateInfoInstance()
    {
        $this->assertInstanceOf('Rentgen\Schema\Info', $this->rentgen->createInfoInstance());
    }
}
