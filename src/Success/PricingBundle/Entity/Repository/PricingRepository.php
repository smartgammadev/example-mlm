<?php

namespace Success\PricingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PricingRepository extends EntityRepository
{
    public function findCurrentPricing()
    {
        $result =
            $this->getEntityManager()->createQueryBuilder()
                ->select('p')
                ->from('SuccessPricingBundle:Pricing', 'p')
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        return $result;
    }
}
