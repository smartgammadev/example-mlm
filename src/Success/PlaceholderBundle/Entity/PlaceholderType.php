<?php

namespace Success\PlaceholderBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PlaceholderType
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\PlaceholderBundle\Entity\PlaceholderTypeRepository")
 */
class PlaceholderType
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
     * @ORM\Column(name="name", type="string", unique=true, length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="pattern", type="string", unique=true, length=50)
     */
    private $pattern;
    
    
    /**
     * @ORM\OneToMany(targetEntity="BasePlaceholder", mappedBy="placeholderType")
     */
    private $placeholders;
    
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
     * @return PlaceholderType
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
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add placeholders
     *
     * @param \Success\PlaceholderBundle\Entity\BasePlaceholder $placeholders
     * @return PlaceholderType
     */
    public function addPlaceholder(\Success\PlaceholderBundle\Entity\BasePlaceholder $placeholders)
    {
        $this->placeholders[] = $placeholders;

        return $this;
    }

    /**
     * Remove placeholders
     *
     * @param \Success\PlaceholderBundle\Entity\BasePlaceholder $placeholders
     */
    public function removePlaceholder(\Success\PlaceholderBundle\Entity\BasePlaceholder $placeholders)
    {
        $this->placeholders->removeElement($placeholders);
    }

    /**
     * Get placeholders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }
    
    public function __toString() {
        return $this->name;
    }

    /**
     * Set pattern
     *
     * @param string $pattern
     * @return PlaceholderType
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Get pattern
     *
     * @return string 
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}
