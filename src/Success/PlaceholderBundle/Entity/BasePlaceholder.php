<?php

namespace Success\PlaceholderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BasePlaceholder
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base_placeholder" = "BasePlaceholder", "external_placeholder" = "ExternalPlaceholder"})
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="full_pattern", type="string", length=255, unique=true)
     */
    private $fullPattern;

    /**
     * @ORM\Column(name="allow_user_to_edit", type="boolean")
     */
    private $allowUserToEdit;

    /**
     * @ORM\Column(name="pass_to_external_link", type="boolean")
     */
    private $passToExternalLink;

    
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
     *     *
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

    /**
     * @ORM\PrePersist
     * @return void 
     */
    public function setFullPattern()
    {
        $this->fullPattern = $this->getPlaceholderType()->getPattern().'_'.$this->pattern;
    }

    /**
     * Get fullPattern
     *
     * @return string 
     */
    public function getFullPattern()
    {
        return $this->fullPattern;
    }


    /**
     * Set allowUserToEdit
     *
     * @param boolean $allowUserToEdit
     * @return BasePlaceholder
     */
    public function setAllowUserToEdit($allowUserToEdit)
    {
        $this->allowUserToEdit = $allowUserToEdit;
        return $this;
    }

    /**
     * Get allowUserToEdit
     *
     * @return boolean 
     */
    public function getAllowUserToEdit()
    {
        return $this->allowUserToEdit;
    }

    /**
     * Set passToExternalLink
     *
     * @param boolean $passToExternalLink
     * @return BasePlaceholder
     */
    public function setPassToExternalLink($passToExternalLink)
    {
        $this->passToExternalLink = $passToExternalLink;

        return $this;
    }

    /**
     * Get passToExternalLink
     *
     * @return boolean 
     */
    public function getPassToExternalLink()
    {
        return $this->passToExternalLink;
    }
}
