<?php

namespace AppBundle;

use AppBundle\Entity\Product;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\ValueConverter\StringToDateTimeValueConverter;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * Import products to database
     *
     * @param ContainerInterface $container
     * @return array
     */
    public function saveProducts($container)
    {
        $response = [
            'general' => [
                'processed' => 0,
                'successful' => 0,
                'skipped' => 0,
            ],
            'errors_description' => [],
        ];
        $validProducts = [];

        foreach ($this->getReader() as $row) {
            $response['general']['processed']++;

            $product = new Product();
            $product->setStrProductCode($row[$container->getParameter('scv_product_code')]);
            $product->setStrProductName($row[$container->getParameter('scv_product_name')]);
            $product->setStrProductDesc($row[$container->getParameter('scv_product_description')]);
            $product->setIntProductStock((int)$row[$container->getParameter('scv_product_stock')]);
            $product->setIntProductCost($row[$container->getParameter('scv_product_cost')]);

            $validator = $container->get('validator');
            $errors = $validator->validate($product);

            if (count($errors) > 0) {
                $response['general']['skipped']++;
                $response['errors_description'][] = [
                    'product_code' => $product->getStrProductCode(),
                    'errors' => (string)$errors,
                ];
            } else {
                $encoders = [new XmlEncoder(), new JsonEncoder()];
                $normalizers = [new ObjectNormalizer()];
                $serializer = new Serializer($normalizers, $encoders);

                $normalizedProduct = $serializer->normalize($product);
                $product->setIfDiscontinued(
                    $row[$container->getParameter('scv_product_discontinued')],
                    $container->getParameter('scv_product_discontinued_success')
                );
                $normalizedProduct['dtmDiscontinued'] = $product->getDtmDiscontinued();
                $normalizedProduct['dtmAdded'] = new \DateTime();
                $validProducts[] = $normalizedProduct;
            }
        }

        $failedRows = $this->fillDb($container->get('doctrine'), $validProducts);
        $response['general']['skipped'] += $failedRows;
        $response['general']['successful'] = $response['general']['processed'] - $response['general']['skipped'];

        return $response;
    }

    /**
     * Parsing csv-file
     *
     * @return CsvReader
     */
    public function getReader()
    {
        $reader = new CsvReader($this->_file);
        $reader->setHeaderRowNumber(0);
//        $reader->setColumnHeaders(
//            [
//                'strProductCode',
//                'strProductName',
//                'strProductDesc',
//                'intProductStock',
//                'intProductCost',
//                'Discontinued',
//            ]
//        );
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
        /**
         * @var DoctrineWriter $writer
         */
        $writer = new DoctrineWriter($doctrine->getManager(), 'AppBundle:Product', 'strProductCode');
        $writer->prepare();
        for ($i = 0; $i < count($items); $i += $pocketSize) {
            for ($j = 0; $i + $j < count($items) && $j < $pocketSize; $j++) {
                $writer->writeItem($items[$i + $j]);
            }
            try {
                $writer->finish();
            } catch (UniqueConstraintViolationException $e) {
                $failedRows++;
                $doctrine->resetManager();
                $writer = new DoctrineWriter($doctrine->getManager(), 'AppBundle:Product', 'strProductCode');
            }
        }

        return $failedRows;
    }
}