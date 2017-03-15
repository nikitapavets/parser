<?php

namespace AppBundle\Command;

use AppBundle\Parser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\ConstraintViolation;

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
        $parser = $this->getContainer()->get('app.parser');
        $scvFileName = $this->getContainer()->getParameter('kernel.root_dir').$input->getArgument('filename');
        $parser->setFilename($scvFileName);

        $report = $parser->process();
        $this->showReport($report, $output);
    }

    /**
     * @param array $report
     * @param OutputInterface $output
     */
    private function showReport($report, OutputInterface $output)
    {
        if ($report) {
            $output->writeln(
                'Processed: '.$report['general']['processed'].
                ', Successful: '.$report['general']['successful'].
                ', Skipped: '.$report['general']['skipped']
            );
            if ($report['no_validated_products']) {
                $output->writeln('Import errors:');
                foreach ($report['no_validated_products'] as $productCode => $productErrors) {
                    $output->writeln('Product '.$productCode.' failed with errors:');
                    foreach ($productErrors as $productError) {
                        /**
                         * @var ConstraintViolation $productError
                         */
                        $output->writeln(
                            ($productError->getPropertyPath() ? $productError->getPropertyPath().' - ' : '').
                            $productError->getMessage()
                        );
                    }
                }
            }
        }
    }
}
