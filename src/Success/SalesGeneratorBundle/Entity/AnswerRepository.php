<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
    /**
     * @param Success/SalesGeneratorBundle/Entity/Question $question
     */
    public function removeNextQuestionForReferencingAnswer(\Success\SalesGeneratorBundle\Entity\Question $question)
    {
        if ($referencingAnswer = $this->findOneBy(['nextQuestion' => $question->getId()])) {
            $referencingAnswer->setNextQuestion();
        }
    }
    
    public function removeAllAnswersForQuestion(\Success\SalesGeneratorBundle\Entity\Question $question)
    {
        $em = $this->em;
        
        foreach($question->getAnswers() as $answer) {
            // find all answers that refference current one and set this reff to NULL
            $this->removeNextQuestionForReferencingAnswer($question);

            $question->removeAnswer($answer);
            $em->remove($answer);
            $em->flush();
        }
    }
}
