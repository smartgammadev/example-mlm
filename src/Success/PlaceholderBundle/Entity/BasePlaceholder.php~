<?php

namespace Success\PlaceholderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BasePlaceholder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\PlaceholderBundle\Entity\BasePlaceholderRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base_placeholder" = "BasePlaceholder", "external_placeholder" = "ExternalPlaceholder"})
 * 
 */

class BasePlaceholder
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="pattern", type="string", length=100)
     */
    private $pattern;

    /**
     * @ORM\ManyToOne(targetEntity="PlaceholderType", inversedBy="placeholders")
     * @ORM\JoinColumn(name="placeholder_type_id", referencedColumnName="id")
     */
    private $placeholderType;


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
     * @return BasePlaceholder
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
     * Set pattern
     *
     * @param string $pattern
     * @return BasePlaceholder
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
    
    /**
     * 
     * @return string
     */
    public function __toString() 
    {
        return $this->name;
    }

    /**
     * Set placeholderType
     *
     * @param \Success\PlaceholderBundle\Entity\PlaceholderType $placeholderType
     * @return BasePlaceholder
     */
    public function setPlaceholderType(\Success\PlaceholderBundle\Entity\PlaceholderType $placeholderType = null)
    {
        $this->placeholderType = $placeholderType;

        return $this;
    }

    /**
     * Get placeholderType
     *
     * @return \Success\PlaceholderBundle\Entity\PlaceholderType 
     */
    public function getPlaceholderType()
    {
        return $this->placeholderType;
    }
}
