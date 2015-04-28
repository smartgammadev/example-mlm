<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\SalesGeneratorBundle\Entity\AnswerRepository")
 */
class Answer
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
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\SalesGeneratorBundle\Entity\Question", inversedBy="answers", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="current_question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $currentQuestion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\SalesGeneratorBundle\Entity\Question", cascade={"persist"})
     * @ORM\JoinColumn(name="next_question_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $nextQuestion;
    
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
     * @return Answer
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
     * Set currentQuestion
     *
     * @param \Success\SalesGeneratorBundle\Entity\Question $currentQuestion
     * @return Success\SalesGeneratorBundle\Entity\Answer
     */
    public function setCurrentQuestion(\Success\SalesGeneratorBundle\Entity\Question $currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;

        return $this;
    }

    /**
     * Get currentQuestion
     *
     * @return \Success\SalesGeneratorBundle\Entity\Question
     */
    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }
    
    /**
     * Set nextQuestion
     *
     * @param $nextQuestion
     * @return Success\SalesGeneratorBundle\Entity\Answer
     */
    public function setNextQuestion(\Success\SalesGeneratorBundle\Entity\Question $nextQuestion = null)
    {
        $this->nextQuestion = $nextQuestion;

        return $this;
    }

    /**
     * Get nextQuestion
     *
     * @return \Success\SalesGeneratorBundle\Entity\Question
     */
    public function getNextQuestion()
    {
        return $this->nextQuestion;
    }
    
    public function __toString()
    {
        return $this->text;
    }
}
