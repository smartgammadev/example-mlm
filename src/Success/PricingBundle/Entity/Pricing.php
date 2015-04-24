<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\PricingBundle\Entity\PricingValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Pricing
 *
 * @ORM\Table(name="p_pricing")
 * @ORM\Entity(repositoryClass="Success\PricingBundle\Entity\Repository\PricingRepository")
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
     * @var string
     * @ORM\Column(name="pricing_name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="levels_up", type="integer", nullable=false)
     */
    private $levelsUp;
    

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="PricingValue", mappedBy="pricing")
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
        $this->pricingValues = new ArrayCollection();
    }

    /**
     * Add pricingValues
     *
     * @param \Success\PricingBundle\Entity\PricingValues $pricingValues
     * @return Pricing
     */
    public function addPricingValue(PricingValue $pricingValues)
    {
        $this->pricingValues[] = $pricingValues;

        return $this;
    }

    /**
     * Remove pricingValues
     *
     * @param \Success\PricingBundle\Entity\PricingValues $pricingValues
     */
    public function removePricingValue(PricingValues $pricingValues)
    {
        $this->pricingValues->removeElement($pricingValues);
    }

    /**
     * Get pricingValues
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPricingValues()
    {
        return $this->pricingValues;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Pricing
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
}
