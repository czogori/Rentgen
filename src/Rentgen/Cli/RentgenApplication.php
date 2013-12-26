<?php

namespace Rentgen\Cli;

use Symfony\Component\Console\Application;

use Rentgen\Cli\Command\ListTablesCommand;
use Rentgen\Cli\Command\TableInfoCommand;
use Rentgen\Rentgen;

class RentgenApplication extends Application
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('Rentgen - Database info and schema manipulation', '0.9.1');

        $rentgen = new Rentgen();
        $container = $rentgen->getContainer();                

        $this->addCommands(array(
            new TableInfoCommand('table', $container),
            new ListTablesCommand('tables', $container),
        ));
    }
}
