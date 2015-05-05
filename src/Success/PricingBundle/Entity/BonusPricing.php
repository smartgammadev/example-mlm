<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BonusPricing
 *
 * @ORM\Table(name="p_bonus_pricing")
* @ORM\Entity(repositoryClass="Success\PricingBundle\Entity\Repository\BonusPricingRepository")
 */
class BonusPricing
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
     * @ORM\Column(name="pricingName", type="string", length=255, nullable=false)
     */
    private $pricingName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="BonusPricingValue", mappedBy="pricing", cascade={"all"}, orphanRemoval=true)
     */
    private $pricingValues;
    
    
    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pricingName
     *
     * @param string $pricingName
     * @return BonusPricing
     */
    public function setPricingName($pricingName)
    {
        $this->pricingName = $pricingName;

        return $this;
    }

    /**
     * Get pricingName
     * @return string
     */
    public function getPricingName()
    {
        return $this->pricingName;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return BonusPricing
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @param \Success\PricingBundle\Entity\BonusPricingValue $pricingValues
     * @return BonusPricing
     */
    public function addPricingValue(\Success\PricingBundle\Entity\BonusPricingValue $pricingValues)
    {
        $this->pricingValues[] = $pricingValues;

        return $this;
    }

    /**
     * Remove pricingValues
     *
     * @param \Success\PricingBundle\Entity\BonusPricingValue $pricingValues
     */
    public function removePricingValue(\Success\PricingBundle\Entity\BonusPricingValue $pricingValues)
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
}
