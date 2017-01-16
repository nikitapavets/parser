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
     * @param EntityManagerInterface $entityManager
     * @param array $item
     */
    public function fillDb($entityManager, $item)
    {
        print_r($item);
        $writer = new DoctrineWriter($entityManager, 'AppBundle:Product', 'strProductCode');
        $writer->prepare();
        $writer->writeItem($item);
        $writer->finish();
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
        /*$em = $container->get('doctrine')->getManager();

        $workflow = new Workflow($reader);
        $doctrineWriter = new DoctrineWriter($em, 'AppBundle:Product');
        $workflow->addWriter($doctrineWriter);
        $workflow->process(); */

        $em = $container->get('doctrine')->getManager();

        $response = array();
        foreach ($reader as $row)
		{
            $product = new Product();
            $product->setCode($row[$container->getParameter('scv_product_code')]);
            $product->setName($row[$container->getParameter('scv_product_name')]);
            $product->setDesc($row[$container->getParameter('scv_product_description')]);
            $product->setStock((int)$row[$container->getParameter('scv_product_stock')]);
            $product->setCost($row[$container->getParameter('scv_product_cost')]);
            $product->setAddedAt(new \DateTime());

            $validator = $container->get('validator');
            $errors = $validator->validate($product);

            if (count($errors) > 0)
            {
                $response[] = [
                    'element' => $product->getCode(),
                    'errors' => (string)$errors
                ];
            }
            else
            {
                $item = array(
                  'name' => 'asdasd',
                );
                $this->fillDb($em, $item);
            }
        }

        return $response;


        /*if($row['Discontinued'] == 'yes')
        {
            $product->setDiscontinuedAt(new \DateTime());
        }*/



//		$report = array(
//			'processed' => 0,
//			'successful' => 0,
//			'skipped' => 0,
//			'skipped_items' => array(),
//		);
//
//		foreach ($reader as $row)
//		{
//			$report['processed']++;
//
//			if(!((((int)$row[$container->getParameter('scv_product_cost')] < 5) && ((int)$row['Stock'] < 10)) || ((int)$row['Cost in GBP'] > 1000)))
//			{
//				$product = new Product();
//				$product->setCode($row['Product Code']);
//				$product->setName($row['Product Name']);
//				$product->setDesc($row['Product Description']);
//				if(!is_numeric($row['Stock']))
//				{
//					$report['skipped']++;
//					$row['error'] = 'column `Stock` is invalid';
//					$report['skipped_items'][] = $row;
//					continue;
//				}
//				$product->setStock($row['Stock']);
//				if(!is_numeric($row['Cost in GBP']))
//				{
//					$report['skipped']++;
//					$row['error'] = 'column `Cost in GBP` is invalid';
//					$report['skipped_items'][] = $row;
//					continue;
//				}
//				$product->setCost((double)$row['Cost in GBP']);
//				$product->setAddedAt(new \DateTime());
//				if($row['Discontinued'] == 'yes')
//				{
//					$product->setDiscontinuedAt(new \DateTime());
//				}
//
//				$em = $container->get('doctrine')->getManager();
//				try
//				{
//					$em->persist($product);
//					$em->flush();
//				}
//				catch (UniqueConstraintViolationException $e)
//				{
//					$container->get('doctrine')->resetManager();
//					$report['skipped']++;
//					$row['error'] = 'product with this code is exists';
//					$report['skipped_items'][] = $row;
//					continue;
//				}
//				$report['successful']++;
//			}
//			else
//			{
//				$report['skipped']++;
//				$row['error'] = 'mismatch condition';
//				$report['skipped_items'][] = $row;
//			}
//		}
//
//		foreach ($reader as $row)
//		{
//			$product = $container->get('doctrine')
//				->getRepository('AppBundle:Product')
//				->findOneBy(array('code' => $row['Product Code']));
//			if($product)
//			{
//				$em = $container->get('doctrine')->getManager();
//				$em->remove($product);
//				$em->flush();
//			}
//		}
//
//		return $report;
	}
}