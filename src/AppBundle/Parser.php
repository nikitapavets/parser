<?php

namespace AppBundle;

use AppBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Port\Steps\StepAggregator as Workflow;
use Port\Csv\CsvReader;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\Step\ValueConverterStep;
use SplFileObject;

class Parser implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $_container;

    /**
     * @var SplFileObject
     */
    private $_file;

    /**
     * Get file resource by file name
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->_file = new SplFileObject($filename);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->_container = $container;
    }

    /**
     * @return array
     */
    public function process()
    {
        $workflow = new Workflow($this->getReader());
        $workflow->addWriter($this->getWriter());
        $workflow->setSkipItemOnFailure(true);

        $productValidatorStep = $this->_container->get('app.validators.product_validator_step');
        $workflow->addStep($productValidatorStep);

        $converterStep = new ValueConverterStep();
        $converterStep->add(
            '['.$this->_container->getParameter('scv_product_discontinued').']',
            function ($name) {
                return Product::validateIsDiscontinued(
                    $name,
                    $this->_container->getParameter('scv_product_discontinued_success')
                );
            }
        );
        $workflow->addStep($converterStep);

        $result = $workflow->process();
        $response = [
            'general' => [
                'processed' => $result->getTotalProcessedCount(),
                'successful' => $result->getSuccessCount(),
                'skipped' => $result->getErrorCount(),
            ],
            'no_validated_products' => $productValidatorStep->getViolations(),
        ];

        return $response;
    }

    /**
     * @return CsvReader
     */
    public function getReader()
    {
        $reader = new CsvReader($this->_file);
        $reader->setHeaderRowNumber(0);
        $reader->setColumnHeaders(
            [
                $this->_container->getParameter('scv_product_code'),
                $this->_container->getParameter('scv_product_name'),
                $this->_container->getParameter('scv_product_description'),
                $this->_container->getParameter('scv_product_stock'),
                $this->_container->getParameter('scv_product_cost'),
                $this->_container->getParameter('scv_product_discontinued'),
            ]
        );
        $reader->setStrict(false);

        return $reader;
    }

    /**
     * @return DoctrineWriter
     */
    public function getWriter()
    {
        $writer = new DoctrineWriter($this->_container->get('doctrine')->getManager(), 'AppBundle:Product');

        return $writer;
    }
}
