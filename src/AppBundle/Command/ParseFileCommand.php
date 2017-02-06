<?php

namespace AppBundle\Command;

use AppBundle\Parser;
use AppBundle\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:parse-scv')
            ->setDescription('Parse scv file.')
            ->setHelp("This command allows you to parse scv files...")
            ->addArgument('filename', InputArgument::REQUIRED, 'The scv file name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var Parser $parser
         */
        $parser = $this->getContainer()->get('app.parser');
        $parser->setFilename($this->getContainer()->getParameter('kernel.root_dir').$input->getArgument('filename'));
        $report = $parser->saveProducts($this->getContainer());
        if ($report) {
            $output->writeln(
                'Processed: '.$report['general']['processed'].
                ', Successful: '.$report['general']['successful'].
                ', Skipped: '.$report['general']['skipped']
            );
            if ($report['errors_description']) {
                $output->writeln('Errors:');
                foreach ($report['errors_description'] as $errorDescription) {
                    $output->writeln($errorDescription['product_code']);
                    $output->writeln($errorDescription['errors']);
                }
            }
        }

    }
}