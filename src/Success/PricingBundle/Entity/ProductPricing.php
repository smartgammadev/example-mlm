<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPricing
 *
 * @ORM\Table(name="p_product_pricing")
 * @ORM\Entity
 */
class ProductPricing
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="boolean", length=255)
     */
    private $isActive;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255)
     */
    private $productName;

    /**
     * @var string
     * @ORM\Column(name="product_price", type="decimal", precision=10, scale=2)
     */
    private $productPrice;

    
    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime();
    }
    

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     * @return ProductPricing
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productPrice
     *
     * @param string $productPrice
     * @return ProductPricing
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return string
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ProductPricing
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return ProductPricing
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    public function __toString()
    {
        return $this->getProductName();
    }
}
