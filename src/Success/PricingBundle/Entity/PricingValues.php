<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\PricingBundle\Entity\Pricing;

/**
 * PricingValues
 *
 * @ORM\Table(name="p_pricing_values")
 * @ORM\Entity
 */
class PricingValues
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
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isAbsoluteValue", type="boolean")
     */
    private $isAbsoluteValue;

    /**
     * @var string
     *
     * @ORM\Column(name="profitValue", type="decimal", precision=10, scale=2)
     */
    private $profitValue;

    /**
     * @ORM\ManyToOne(targetEntity="Pricing", inversedBy="pricingValues")
     * @ORM\JoinColumn(name="pricing_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $pricing;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return PricingValues
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isAbsoluteValue
     *
     * @param boolean $isAbsoluteValue
     * @return PricingValues
     */
    public function setIsAbsoluteValue($isAbsoluteValue)
    {
        $this->isAbsoluteValue = $isAbsoluteValue;

        return $this;
    }

    /**
     * Get isAbsoluteValue
     *
     * @return boolean
     */
    public function getIsAbsoluteValue()
    {
        return $this->isAbsoluteValue;
    }

    /**
     * Set profitValue
     *
     * @param string $profitValue
     * @return PricingValues
     */
    public function setProfitValue($profitValue)
    {
        $this->profitValue = $profitValue;

        return $this;
    }

    /**
     * Get profitValue
     *
     * @return string
     */
    public function getProfitValue()
    {
        return $this->profitValue;
    }

    /**
     * Set pricing
     *
     * @param \Success\PricingBundle\Entity\Pricing $pricing
     * @return PricingValues
     */
    public function setPricing(Pricing $pricing)
    {
        $this->pricing = $pricing;

        return $this;
    }

    /**
     * Get pricing
     *
     * @return \Success\PricingBundle\Entity\Pricing
     */
    public function getPricing()
    {
        return $this->pricing;
    }
}
