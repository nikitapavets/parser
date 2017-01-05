<?php

namespace AppBundle;


use Ddeboer\DataImport\Reader\CsvReader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Parser
{
	private $_file;

	/**
	 * Set file parameters
	 *
	 * @param string $fileName
	 */
	public function setParameters($fileName)
	{
		$this->_file = new \SplFileObject($fileName);
	}

	/**
	 * Parse scv file to array
	 *
	 * @return CsvReader
	 */
	public function readFile()
	{
		$reader = new CsvReader($this->_file);
		$reader->setHeaderRowNumber(0);

		return $reader;
	}
}