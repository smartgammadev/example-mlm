<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{    
    public function removeAllQuestionsFromAudience(\Success\SalesGeneratorBundle\Entity\Audience $audience)
    {   
        $em = $this->getEntityManager();
        $audienceId = $audience->getId();
        
        // Remove all answers
        $questions = $this->findByAudience($audienceId);
        foreach ($questions as $question) {
            $em->getRepository('SuccessSalesGeneratorBundle:Answer')->removeAllAnswersForQuestion($question);
            $em->remove($question);
        }
        $em->flush();
    }
}
