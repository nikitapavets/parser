<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ParseFileCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParseFileCommandTest extends KernelTestCase
{
	/**
	 * Test parsing svg file and import this to database
	 */
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
		$this->assertContains('Processed: 29, Successful: 23, Skipped: 6', $output);
	}
}