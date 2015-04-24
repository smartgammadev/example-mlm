<?php

namespace Success\TreasureBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Success\MemberBundle\Entity\Member;

class AccountOperationRepository extends EntityRepository
{
    public function calculateMemberSubAccountBalance(Member $member, $subAccount)
    {
        $result = $this->getEntityManager()->createQuery(
            'SELECT sum(ao.amount) from SuccessTreasureBundle:AccountOperation ao'
            . ' WHERE ao.subAccount = :sub_account and'
            . ' ao.member = :member'
        )
        ->setParameter('sub_account', $subAccount)
        ->setParameter('member', $member)
        ->getSingleScalarResult();        
        if (!$result) {
            $result = 0;
        }
        return (float)$result;
    }
}
