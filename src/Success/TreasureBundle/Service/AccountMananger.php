<?php
namespace Success\TreasureBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\TreasureBundle\Entity\AccountOperation;
use Success\TreasureBundle\Entity\AccountBalance;

class AccountMananger
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function doAccountOperation(Member $member, $amount, $subAccount)
    {
        $now = new \DateTime();
        
        $operation = new AccountOperation();
        $operation->setMember($member);
        $operation->setAmount($amount);
        $operation->setSubAccount($subAccount);
        $operation->setDateOperation($now);
        $this->em->persist($operation);
        $this->em->flush();
        $this->updateAccountBalance($member, $amount);
        
        return $operation;
    }
    
    private function updateAccountBalance(Member $member, $amount)
    {
        $balance = $this->getOverallAccountBalance($member);
        if (!$balance) {
            $balance = new AccountBalance();
            $balance->setMember($member);
            $balance->setAmount($amount);
            $this->em->persist($balance);
        } else {
            $balance->setAmount($balance->getAmount() + $amount);
        }
        $this->em->flush();
    }
    
    public function getSubAccountBalance(Member $member, $subAccount)
    {
        $operationRepo = $this->em->getRepository('SuccessTreasureBundle:AccountOperation');
        return $operationRepo->calculateMemberSubAccountBalance($member, $subAccount);
    }
    
    public function getOverallAccountBalance(Member $member)
    {
        /**
         * @var \Doctrine\ORM\EntityRepository $repo
         */
        $balanceRepo = $this->em->getRepository('SuccessTreasureBundle:AccountBalance');
        $balance = $balanceRepo->findOneBy(['member' => $member]);
        
        return $balance;
    }
}
