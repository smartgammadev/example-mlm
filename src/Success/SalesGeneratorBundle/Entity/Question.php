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
    const PREVIEW_QUESTION_MAX_LENGTH = 25;
    
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
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\SalesGeneratorBundle\Entity\Audience")
     * @ORM\JoinColumn(name="audience_id", referencedColumnName="id", nullable=true)
     */
    private $audience;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Success\SalesGeneratorBundle\Entity\Answer", mappedBy="currentQuestion", cascade={"all"}, orphanRemoval=true)
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
     * Set audience
     *
     * @param \Success\SalesGeneratorBundle\Entity\Audience $audience
     * @return Success\SalesGeneratorBundle\Entity\Question
     */
    public function setAudience(\Success\SalesGeneratorBundle\Entity\Audience $audience)
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * Get audience
     *
     * @return \Success\SalesGeneratorBundle\Entity\Audience
     */
    public function getAudience()
    {
        return $this->audience;
    }
    
    /**
     * 
     * @param \Success\SalesGeneratorBundle\Entity\Answer $answer
     * @return \Success\SalesGeneratorBundle\Entity\Question
     */
    public function addAnswer(\Success\SalesGeneratorBundle\Entity\Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $answer->setCurrentQuestion($this);
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
    
    public function __toString()
    {
        $firstQuestion = explode('||', $this->text)[0];
        
        // Limit specific question to specific amount of words
        if (strlen($firstQuestion) > self::PREVIEW_QUESTION_MAX_LENGTH) {
            $position = strpos($this->text, ' ', self::PREVIEW_QUESTION_MAX_LENGTH);
            $questionText = substr($firstQuestion, 0, $position) . '...';
        } else {
            $questionText = $firstQuestion;
        }
        return (string)$this->id . ' ' . $questionText;
    }
}
