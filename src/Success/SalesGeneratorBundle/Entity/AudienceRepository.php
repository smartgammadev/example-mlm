<?php

namespace Success\SalesGeneratorBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AudienceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AudienceRepository extends EntityRepository
{
    /**
     * @param Success/SalesGeneratorBundle/Entity/Question $question
     */
    public function removeReferenceToFirstQuestion($question)
    {
        $referencingAudience = $this->findOneBy(['firstQuestion' => $question->getId()]);
        
        if ($referencingAudience)
            $referencingAudience->setFirstQuestion();
    }
}
