<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Question
 *
 * @ORM\Table(name="Questions")
 * @ORM\Entity(repositoryClass="Success\SalesGeneratorBundle\Entity\QuestionRepository")
 */
class Question
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", length=50)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Success\SalesGeneratorBundle\Entity\Answer", mappedBy="question")
     */
    private $answers;
    
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @return integer 
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
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
     * Set text
     *
     * @param string $text
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }   
    
    /**
     * 
     * @param \Success\SalesGeneratorBundle\Entity\Answer $answer
     * @return \Success\SalesGeneratorBundle\Entity\Question
     */
    public function addAnswer(\Success\SalesGeneratorBundle\Entity\Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $answer->setQuestion($this);
            $this->answers[] = $answer;
        }

        return $this;
    }

    /**
     * 
     * @param \Success\SalesGeneratorBundle\Entity\Answer $answer
     */
    public function removeAnswer(\Success\SalesGeneratorBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
