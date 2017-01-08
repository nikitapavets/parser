<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ParseFileCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParseFileCommandTest extends KernelTestCase
{
	public function testParseStockFile()
	{
		self::bootKernel();
		$application = new Application(self::$kernel);
		$application->add(new ParseFileCommand());

		$command = $application->find('app:parse-scv');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'command'  => $command->getName(),
			'filename' => '/documents/stock.csv',
		));
		$output = $commandTester->getDisplay();
		$this->assertContains('Processed: 27, Successful: 24, Skipped: 3', $output);
	}
}