<?php

namespace AppBundle;

use Ddeboer\DataImport\Workflow\StepAggregator as Workflow;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ValueConverter\StringToDateTimeValueConverter;
use AppBundle\Entity\Product;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Parser
{
	private $_file;

	/**
	 * Get file resourse by file name
	 *
	 * @param string $filename
	 */
	public function setFilename($filename)
	{
		$this->_file = new \SplFileObject($filename);
	}

	/**
	 * Parsing csv-file
	 *
	 * @return CsvReader
	 */
	public function parseFile()
	{
		$reader = new CsvReader($this->_file);
		$reader->setHeaderRowNumber(0);
		$reader->setStrict(false);
		return $reader;
	}

    /**
     * @param $doctrine
     * @param array $items
     * @param int $pocketSize
     * @return int
     */
    public function fillDb($doctrine, $items, $pocketSize = 10)
    {
        $failedRows = 0;
        $writer = new DoctrineWriter($doctrine->getManager(), 'AppBundle:Product', 'strProductCode');
        $writer->prepare();
        for($i = 0; $i < count($items); $i += $pocketSize)
        {
            for($j = 0; $i + $j < count($items) && $j < $pocketSize; $j++)
            {
                $writer->writeItem($items[$i + $j]);
            }
            try
            {
                $writer->finish();
            }
            catch (UniqueConstraintViolationException $e)
            {
                $failedRows++;
                $doctrine->resetManager();
                $writer = new DoctrineWriter($doctrine->getManager(), 'AppBundle:Product', 'strProductCode');
            }
        }
        return $failedRows;
    }

	/**
	 * Import products to database
	 *
	 * @param CsvReader $reader
	 * @param ContainerInterface $container
	 * @return array
	 */
	public function saveProducts($reader, $container)
    {
        $doctrine = $container->get('doctrine');

        $response = array(
            'general' => array(
                'processed' => 0,
                'successful' => 0,
                'skipped' => 0,
            ),
            'desc' => array(),
        );
        $validProducts = array();

        foreach ($reader as $row)
		{
            $response['general']['processed']++;

            $product = new Product();
            $product->setCode($row[$container->getParameter('scv_product_code')]);
            $product->setName($row[$container->getParameter('scv_product_name')]);
            $product->setDesc($row[$container->getParameter('scv_product_description')]);
            $product->setStock((int)$row[$container->getParameter('scv_product_stock')]);
            $product->setCost($row[$container->getParameter('scv_product_cost')]);

            $validator = $container->get('validator');
            $errors = $validator->validate($product);

            if (count($errors) > 0)
            {
                $response['general']['skipped']++;
                $response['desc'][] = [
                    'element' => $product->getCode(),
                    'errors' => (string)$errors
                ];
            }
            else
            {
                $encoders = array(new XmlEncoder(), new JsonEncoder());
                $normalizers = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizers, $encoders);

                $array = $serializer->normalize($product);
                $array['addedAt'] = new \DateTime();
                if($row[$container->getParameter('scv_product_discontinued')] == 'yes')
                {
                    $array['discontinuedAt'] = new \DateTime();
                }
                $validProducts[] = $array;
            }
        }

        $failedRows = $this->fillDb($doctrine, $validProducts);
        $response['general']['skipped'] += $failedRows;
        $response['general']['successful']  = $response['general']['processed'] - $response['general']['skipped'];

        return $response;

	}
}