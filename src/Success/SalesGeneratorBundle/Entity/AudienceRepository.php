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
    
    public function removeReferenceToFirstQuestion(\Success\SalesGeneratorBundle\Entity\Audience $audience)
    {
        $audience->setFirstQuestion();
    }
}
