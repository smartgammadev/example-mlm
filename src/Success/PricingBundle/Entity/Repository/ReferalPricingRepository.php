<?php

namespace Success\PricingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ReferalPricingRepository extends EntityRepository
{
    public function findCurrentReferalPricing()
    {
        $result =
            $this->getEntityManager()->createQueryBuilder()
                ->select('p')
                ->from('SuccessPricingBundle:ReferalPricing', 'p')
                ->orderBy('p.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        return $result;
    }
}
