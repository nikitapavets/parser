<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Product
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="tblProductData")
 * @UniqueEntity("strProductCode")
 * @Assert\Expression(
 *     "(this.getIntProductCost() >= 5 || this.getIntProductStock() >= 10)",
 *     message="Cost should be greater than or equal to 5 and Stock should be greater than or equal to 10."
 * )
 */
class Product
{
    /**
     * @ORM\Column(name="intProductDataId", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $intProductDataId;

    /**
     * @ORM\Column(name="strProductName", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $strProductName;

    /**
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $strProductDesc;

    /**
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     * @Assert\NotBlank()
     */
    private $strProductCode;

    /**
     * @ORM\Column(name="intProductStock", type="integer", options={"unsigned"=true})
     * @Assert\Type("int")
     * @Assert\NotBlank()
     */
    private $intProductStock;

    /**
     * @ORM\Column(name="intProductCost", type="decimal", precision=15, scale=2)
     * @Assert\LessThanOrEqual(1000)
     * @Assert\Type("numeric")
     * @Assert\NotBlank()
     */
    private $intProductCost;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dtmAdded", type="datetime")
     */
    private $dtmAdded;

    /**
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmDiscontinued;

    /**
     * @ORM\Column(name="stmTimestamp", type="datetime", options={"default":0},
     *     columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $stmTimestamp;

    /**
     * @param $discontinuedCell
     * @param $validDiscontinuedCell
     * @return \DateTime|null
     */
    public static function validateIsDiscontinued($discontinuedCell, $validDiscontinuedCell)
    {
        if ($discontinuedCell == $validDiscontinuedCell) {
            return new \DateTime();
        }

        return null;
    }

    /**
     * Get intProductDataId
     *
     * @return integer
     */
    public function getIntProductDataId()
    {
        return $this->intProductDataId;
    }

    /**
     * Get strProductName
     *
     * @return string
     */
    public function getStrProductName()
    {
        return $this->strProductName;
    }

    /**
     * Set strProductName
     *
     * @param string $strProductName
     * @return Product
     */
    public function setStrProductName($strProductName)
    {
        $this->strProductName = $strProductName;

        return $this;
    }

    /**
     * Get strProductDesc
     *
     * @return string
     */
    public function getStrProductDesc()
    {
        return $this->strProductDesc;
    }

    /**
     * Set strProductDesc
     *
     * @param string $strProductDesc
     * @return Product
     */
    public function setStrProductDesc($strProductDesc)
    {
        $this->strProductDesc = $strProductDesc;

        return $this;
    }

    /**
     * Get strProductCode
     *
     * @return string
     */
    public function getStrProductCode()
    {
        return $this->strProductCode;
    }

    /**
     * Set strProductCode
     *
     * @param string $strProductCode
     * @return Product
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

        return $this;
    }

    /**
     * Get intProductStock
     *
     * @return integer
     */
    public function getIntProductStock()
    {
        return $this->intProductStock;
    }

    /**
     * Set intProductStock
     *
     * @param integer $intProductStock
     * @return Product
     */
    public function setIntProductStock($intProductStock)
    {
        $this->intProductStock = (int)$intProductStock;

        return $this;
    }

    /**
     * Get intProductCost
     *
     * @return float
     */
    public function getIntProductCost()
    {
        return $this->intProductCost;
    }

    /**
     * Set intProductCost
     *
     * @param float $intProductCost
     * @return Product
     */
    public function setIntProductCost($intProductCost)
    {
        $this->intProductCost = $intProductCost;

        return $this;
    }

    /**
     * Get dtmAdded
     *
     * @return \DateTime
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * Get dtmDiscontinued
     *
     * @return \DateTime
     */
    public function getDtmDiscontinued()
    {
        return $this->dtmDiscontinued;
    }

    /**
     * Set dtmDiscontinued
     *
     * @param \DateTime $dtmDiscontinued
     * @return Product
     */
    public function setDtmDiscontinued(\DateTime $dtmDiscontinued)
    {
        $this->dtmDiscontinued = $dtmDiscontinued;

        return $this;
    }

    /**
     * Get stmTimestamp
     *
     * @return \DateTime
     */
    public function getStmTimestamp()
    {
        return $this->stmTimestamp;
    }

    /**
     * Set stmTimestamp
     *
     * @param \DateTime $stmTimestamp
     * @return Product
     */
    public function setStmTimestamp(\DateTime $stmTimestamp)
    {
        $this->stmTimestamp = $stmTimestamp;

        return $this;
    }
}
