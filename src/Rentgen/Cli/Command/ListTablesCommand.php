<?php

namespace Rentgen\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;    


class ListTablesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('List tables.')
            ->setDefinition(array(
                new InputArgument('schema_name', InputArgument::OPTIONAL, 'Schema name'),                
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $getTablesCommand = $this->getContainer()->get('get_tables');
        $tables = $getTablesCommand->execute();

        $rows = array();
        foreach ($tables as $table) {            
            $rows[] = array($table->getSchema()->getName(), $table->getName());
        }

        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Schema', 'Name'))
            ->setRows($rows)
            ->render($output);        
    }
}
