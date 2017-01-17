<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Product
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="tblProductData")
 * @Assert\Expression(
 *     "(this.getCost() >= 5 || this.getStock() >= 10)",
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
    private $id;

    /**
     * @ORM\Column(name="strProductName", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $desc;

    /**
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\Column(name="intProductStock", type="integer", options={"unsigned"=true})
     * @Assert\Type("int")
     * @Assert\NotBlank()
     */
    private $stock;

    /**
     * @ORM\Column(name="intProductCost", type="decimal", precision=15, scale=2)
     * @Assert\LessThanOrEqual(1000)
     * @Assert\Type("numeric")
     * @Assert\NotBlank()
     */
    private $cost;

    /**
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $addedAt;

    /**
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $discontinuedAt;

    /**
     * @ORM\Column(name="stmTimestamp", type="datetime", options={"default":0}, columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    private $timestamp;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set desc
     *
     * @param string $desc
     * @return Product
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return Product
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set added_at
     *
     * @param \DateTime $addedAt
     * @return Product
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get added_at
     *
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set discontinued_at
     *
     * @param \DateTime $discontinuedAt
     * @return Product
     */
    public function setDiscontinuedAt($discontinuedAt)
    {
        $this->discontinuedAt = $discontinuedAt;

        return $this;
    }

    /**
     * Get discontinued_at
     *
     * @return \DateTime
     */
    public function getDiscontinuedAt()
    {
        return $this->discontinuedAt;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
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
