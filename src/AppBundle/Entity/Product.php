<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Product
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $product_id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=50)
	 */
	private $product_name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $product_desc;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=255)
	 */
	private $product_code;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime", nullable=true, unique=true)
	 */
	private $added;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $discontinued;

	/**
	 * @var \DateTime
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $timestamp;

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set productDesc
     *
     * @param string $productDesc
     *
     * @return Product
     */
    public function setProductDesc($productDesc)
    {
        $this->product_desc = $productDesc;

        return $this;
    }

    /**
     * Get productDesc
     *
     * @return string
     */
    public function getProductDesc()
    {
        return $this->product_desc;
    }

    /**
     * Set productCode
     *
     * @param string $productCode
     *
     * @return Product
     */
    public function setProductCode($productCode)
    {
        $this->product_code = $productCode;

        return $this;
    }

    /**
     * Get productCode
     *
     * @return string
     */
    public function getProductCode()
    {
        return $this->product_code;
    }

    /**
     * Set added
     *
     * @param \DateTime $added
     *
     * @return Product
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * Get added
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set discontinued
     *
     * @param \DateTime $discontinued
     *
     * @return Product
     */
    public function setDiscontinued($discontinued)
    {
        $this->discontinued = $discontinued;

        return $this;
    }

    /**
     * Get discontinued
     *
     * @return \DateTime
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Product
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
