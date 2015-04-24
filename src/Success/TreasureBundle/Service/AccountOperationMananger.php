<?php
namespace Success\TreasureBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\TreasureBundle\Entity\AccountOperation;

class AccountOperationMananger
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function accountOperation(Member $member, $amount)
    {
        $operation = new AccountOperation();
        $operation->setMember($member);
        $operation->setAmount($amount);
        $this->em->persist($operation);
        $this->em->flush();
    }
    
    public function accountBalance(Member $member)
    {
        $repo = $this->em->getRepository('SuccessTreasureBundle:AccountOperation');
    }
}
