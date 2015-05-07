<?php

namespace Success\StaticPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\PricingBundle\Entity\ProductPricing;

/**
 * PageProductPricing
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PageProductPricing
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
     * @ORM\ManyToOne(targetEntity="Success\StaticPageBundle\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    protected $page;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\PricingBundle\Entity\ProductPricing")
     * @ORM\JoinColumn(name="product_pricing_id", referencedColumnName="id", nullable=false)
     */
    protected $productPricing;
    
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
     * Set page
     *
     * @param \Success\StaticPageBundle\Entity\Page $page
     * @return PageProductPricing
     */
    public function setPage(\Success\StaticPageBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Success\StaticPageBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set productPricing
     *
     * @param \Success\PricingBundle\Entity\ProductPricing $productPricing
     * @return PageProductPricing
     */
    public function setProductPricing(\Success\PricingBundle\Entity\ProductPricing $productPricing)
    {
        $this->productPricing = $productPricing;

        return $this;
    }

    /**
     * Get productPricing
     *
     * @return \Success\PricingBundle\Entity\ProductPricing 
     */
    public function getProductPricing()
    {
        return $this->productPricing;
    }
}
