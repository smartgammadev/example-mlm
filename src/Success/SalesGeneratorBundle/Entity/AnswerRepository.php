<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
    /**
     * @param Success/SalesGeneratorBundle/Entity/Question $question
     */
    public function removeNextQuestionForReferencingAnswer($question)
    {
        $referencingAnswer = $this->findOneBy(['nextQuestion' => $question->getId()]);
        if ($referencingAnswer)
            $referencingAnswer->setNextQuestion();
    }
}
