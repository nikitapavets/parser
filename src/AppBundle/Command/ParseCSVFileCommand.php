<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ParseCSVFileCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('app:parse-scv')
			->setDescription('Parse scv file.')
			->setHelp("This command allows you to parse scv files...")
			->addArgument('filename', InputArgument::REQUIRED, 'The scv file name.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'User Creator',
			'============',
			'',
		]);

		$parser = $this->getContainer()->get('app.parser');
		$parser->setParameters($this->getContainer()->getParameter('kernel.root_dir').$input->getArgument('filename'));
		$reader = $parser->readFile();
		foreach ($reader as $row) {
			$output->writeln($row['Product Description']);
		}

		// outputs a message followed by a "\n"
		$output->writeln($input->getArgument('filename'));

		// outputs a message without adding a "\n" at the end of the line
		$output->write('You are about to ');
		$output->write('create a user.');
	}
}