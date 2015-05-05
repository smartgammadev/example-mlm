<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\PricingBundle\Entity\BonusPricing;

/**
 * BonusPricingValue
 *
 * @ORM\Table(name="p_bonus_pricing_value")
 * @ORM\Entity
 */
class BonusPricingValue
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
     * @var integer
     * @ORM\Column(name="salesCount", type="integer")
     */
    private $salesCount;

    /**
     * @var string
     * @ORM\Column(name="profitValue", type="decimal", precision=10, scale=2)
     */
    private $profitValue;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="BonusPricing", inversedBy="pricingValues", cascade={"persist"})
     * @ORM\JoinColumn(name="bonus_pricing_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $pricing;
    
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
     * Set salesCount
     *
     * @param integer $salesCount
     * @return BonusPricingValue
     */
    public function setSalesCount($salesCount)
    {
        $this->salesCount = $salesCount;

        return $this;
    }

    /**
     * Get salesCount
     * @return integer
     */
    public function getSalesCount()
    {
        return $this->salesCount;
    }

    /**
     * Set profitValue
     *
     * @param string $profitValue
     * @return BonusPricingValue
     */
    public function setProfitValue($profitValue)
    {
        $this->profitValue = $profitValue;

        return $this;
    }

    /**
     * Get profitValue
     * @return string
     */
    public function getProfitValue()
    {
        return $this->profitValue;
    }

    /**
     * Set pricing
     *
     * @param \Success\PricingBundle\Entity\BonusPricing $pricing
     * @return BonusPricingValue
     */
    public function setPricing(BonusPricing $pricing)
    {
        $this->pricing = $pricing;

        return $this;
    }

    /**
     * Get pricing
     * @return \Success\Pricingundle\Entity\BonusPricing
     */
    public function getPricing()
    {
        return $this->pricing;
    }
}
