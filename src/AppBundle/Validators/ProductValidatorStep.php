<?php

namespace AppBundle\Validators;

use Port\Steps\Step\PriorityStep;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use AppBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Port\Exception\ValidationException;

class ProductValidatorStep implements ContainerAwareInterface, PriorityStep
{
    /**
     * @var ValidatorInterface
     */
    private $_validator;

    /**
     * @var ContainerInterface
     */
    private $_container;

    /**
     * @var array
     */
    private $_violations = [];

    /**
     * @var int
     */
    private $_line = 1;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->_container = $container;
        $this->_validator = $container->get('validator');
    }

    /**
     * @param mixed $item
     * @param callable $next
     * @return bool
     * @throws ValidationException
     */
    public function process($item, callable $next)
    {
        $product = new Product();
        $product->setStrProductCode($item[$this->_container->getParameter('scv_product_code')]);
        $product->setStrProductName($item[$this->_container->getParameter('scv_product_name')]);
        $product->setStrProductDesc($item[$this->_container->getParameter('scv_product_description')]);
        $product->setIntProductStock($item[$this->_container->getParameter('scv_product_stock')]);
        $product->setIntProductCost($item[$this->_container->getParameter('scv_product_cost')]);

        $validationErrors = $this->_validator->validate($product);
        if (count($validationErrors) > 0) {
            $this->_violations[$item[$this->_container->getParameter('scv_product_code')]] = $validationErrors;
            throw new ValidationException($validationErrors, $this->_line);
        } else {
            return $next($item);
        }
    }

    /**
     * Get violations from validation step
     *
     * @return array
     */
    public function getViolations()
    {
        return $this->_violations;
    }

    /**
     * Get step priority
     *
     * @return int
     */
    public function getPriority()
    {
        return 16;
    }
}
