<?php

namespace Success\PricingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BonusPricingRepository extends EntityRepository
{
    public function findCurrentBonusPricing()
    {
        $result =
            $this->getEntityManager()->createQueryBuilder()
                ->select('p')
                ->from('SuccessPricingBundle:BonusPricing', 'p')
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        return $result;
    }
}
