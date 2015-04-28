<?php

namespace Success\PricingBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Success\MemberBundle\Entity\Member;

class ProductPricingMemberRepository extends EntityRepository
{
    public function findLastProductPricingForMember(Member $member)
    {
        $result =
            $this->getEntityManager()->createQueryBuilder()
                ->select('p')
                ->from('SuccessPricingBundle:ProductPricingMember', 'p')
                ->where('p.member = :member')
                ->setParameter('member', $member)
                ->orderBy('p.assignDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        return $result;
    }
}
