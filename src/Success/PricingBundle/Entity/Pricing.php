<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\PricingBundle\Entity\PricingValues;

/**
 * Pricing
 *
 * @ORM\Table(name="p_pricing")
 * @ORM\Entity
 */
class Pricing
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
     * @ORM\Column(name="levelsUp", type="integer", nullable=false)
     */
    private $levelsUp;
    

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="PricingValues", mappedBy="pricing")
     */
    private $pricingValues;
    


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
     * Set levelsUp
     *
     * @param integer $levelsUp
     * @return Pricing
     */
    public function setLevelsUp($levelsUp)
    {
        $this->levelsUp = $levelsUp;

        return $this;
    }

    /**
     * Get levelsUp
     *
     * @return integer
     */
    public function getLevelsUp()
    {
        return $this->levelsUp;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pricingValues = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pricingValues
     *
     * @param \Success\PricingBundle\Entity\PricingValues $pricingValues
     * @return Pricing
     */
    public function addPricingValue(\Success\PricingBundle\Entity\PricingValues $pricingValues)
    {
        $this->pricingValues[] = $pricingValues;

        return $this;
    }

    /**
     * Remove pricingValues
     *
     * @param \Success\PricingBundle\Entity\PricingValues $pricingValues
     */
    public function removePricingValue(\Success\PricingBundle\Entity\PricingValues $pricingValues)
    {
        $this->pricingValues->removeElement($pricingValues);
    }

    /**
     * Get pricingValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPricingValues()
    {
        return $this->pricingValues;
    }
}
