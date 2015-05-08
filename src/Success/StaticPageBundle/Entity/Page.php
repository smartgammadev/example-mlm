<?php

namespace Success\StaticPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity()
 * @ORM\Table(name="page")
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    
    /**
     * @ORM\Column(type="boolean")
     */    
    protected $isActive;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Success\StaticPageBundle\Entity\PageProductPricing", mappedBy="page", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $productPricings;    
    
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
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Page
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productPricings = new \Doctrine\Common\Collections\ArrayCollection();
    }
        
    public function __toString()
    {
        return $this->getSlug();
    }

    /**
     * Add productPricings
     *
     * @param \Success\StaticPageBundle\Entity\PageProductPricing $productPricings
     * @return Page
     */
    public function addProductPricing(\Success\StaticPageBundle\Entity\PageProductPricing $productPricings)
    {
        $this->productPricings[] = $productPricings;

        return $this;
    }

    /**
     * Remove productPricings
     *
     * @param \Success\StaticPageBundle\Entity\PageProductPricing $productPricings
     */
    public function removeProductPricing(\Success\StaticPageBundle\Entity\PageProductPricing $productPricings)
    {
        $this->productPricings->removeElement($productPricings);
    }

    /**
     * Get productPricings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductPricings()
    {
        return $this->productPricings;
    }
}
