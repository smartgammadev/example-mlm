<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Audience
 *
 * @ORM\Table(name="sg_audience")
 * @ORM\Entity(repositoryClass="Success\SalesGeneratorBundle\Entity\AudienceRepository")
 */
class Audience
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\SalesGeneratorBundle\Entity\Question")
     * @ORM\JoinColumn(name="first_question_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $firstQuestion;
    
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }
    
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
     * @param integer $id
     * @return Audience
     */
    public function setId($id) 
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Audience
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
     * Set firstQuestion
     *
     * @param \Success\SalesGeneratorBundle\Entity\Question $firstQuestion
     * @return Success\SalesGeneratorBundle\Entity\Audience
     */
    public function setFirstQuestion(\Success\SalesGeneratorBundle\Entity\Question $firstQuestion = null)
    {
        $this->firstQuestion = $firstQuestion;

        return $this;
    }

    /**
     * Get firstQuestion
     *
     * @return \Success\SalesGeneratorBundle\Entity\Question
     */
    public function getFirstQuestion()
    {
        return $this->firstQuestion;
    }
    
    public function __toString()
    {
        return $this->name;
    }
}
