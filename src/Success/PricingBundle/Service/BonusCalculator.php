<?php
namespace Success\PricingBundle\Service;

use Success\PricingBundle\Entity\BonusPricing;
use Success\PricingBundle\Entity\BonusPricingValue;
use Success\MemberBundle\Entity\Member;
use Success\PricingBundle\Utils\DateRange;

class BonusCalculator
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    use \Success\MemberBundle\Traits\SetMemberManagerTrait;
    
    use \Success\PricingBundle\Traits\BonusPricingManagerTrait;
    
    /**
     * @param Member $sponsor
     * @param DateRange $dateRange
     * @return Array
     */
    public function getMemberFirstReferalsHasProduct(Member $sponsor, DateRange $dateRange = null)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $result = $memberRepo->childrenHasProduct($sponsor, true, $dateRange);
        return $result;
    }

    /**
     * @param Member $sponsor
     * @return Array
     */
    public function getMemberFirstReferalsHasProductCount(Member $sponsor)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $result = $memberRepo->childrenHasProductCount($sponsor);
        return $result;
    }

    
    /**
     * @param Member $sponsor
     * @param type $level
     * @return integer
     */
    public function getMemberReferalsHasProductCount(Member $sponsor, $level = null)
    {

        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $result = $memberRepo->childrenHasProductCount($sponsor, $level);
        return $result;
    }

    
    /**
     * @param Member $sponsor
     * @return float
     */
    public function getMemberReferalsHasProductPaidSum(Member $sponsor)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $result = $memberRepo->childrenHasProductPaidSum($sponsor);
        return $result;
    }
    
    /**
     * @deprecated
     * @param Member $member
     * @return type
     */
    public function calculateBonusForMember(Member $member)
    {
        $memberBaseBonus = $this->calculateMemberBaseBonus($member);
        if (!isset($memberBaseBonus)) {
            return null;
        }
        $memberReferals = $this->memberManager->getMemberFirstReferalsHasProduct($member);
        foreach ($memberReferals as $memberReferal) {
            $memberReferalBaseBonus = $this->calculateBonusForMember($memberReferal);
            if ($memberReferalBaseBonus) {
                //echo '////////'.$memberReferal->getExternalId().' TAKES from '.$member->getExternalId().PHP_EOL;
                $memberBaseBonus = $this->getBonusesDiffs($memberBaseBonus, $memberReferalBaseBonus);
                //print_r($memberBaseBonus);
            }
        }
        //print_r($memberBaseBonus);
        return $memberBaseBonus;
    }
    
    /**
     * @deprecated
     * @param array $bonus1
     * @param atray $bonus2
     * @return atray
     */
    private function getBonusesDiffs($bonus1, $bonus2)
    {
        if (array_key_exists('add', $bonus1)) {
            $additionalBonuses1 = $bonus1['add'];
        } else {
            $additionalBonuses1 = [];
        }
        if (array_key_exists('add', $bonus2)) {
            $additionalBonuses2 = $bonus2['add'];
        } else {
            $additionalBonuses2 = [];
        }
        $additionalBonuses = array_merge($additionalBonuses1, $additionalBonuses2);
        
        $additionalBonuses[] = [
            'referalsPaidSum' => $bonus2['referalsPaidSum'],
            'salesCount' => $bonus2['salesCount'],
            'profitValue' => $bonus1['profitValue'] - $bonus2['profitValue'],
            
        ];
        //echo $bonus1['profitValue'].' - '.$bonus2['profitValue'].PHP_EOL;
        $result = [
                    'referalsPaidSum' => $bonus1['referalsPaidSum'] - $bonus2['totalReferalsPaidSum'],
                    'totalReferalsPaidSum' => $bonus1['referalsPaidSum'],
            
                    'salesCount' => $bonus1['salesCount'] - $bonus2['totalSalesCount'],
                    'totalSalesCount' => $bonus1['salesCount'],
            
                    'profitValue' => $bonus1['profitValue'],// - $bonus2['profitValue'],
                    'add' => $additionalBonuses,
        ];
        return $result;
    }


    /**
     * @deprecated
     * @param Member $member
     * @return type
     */
    public function calculateMemberBaseBonus(Member $member)
    {
        $bonusPricing = $this->getCurrentBonusPricing();
        $bonusPricingValues = $bonusPricing->getPricingValues();
        $actualSalesCount = $this->memberManager->getMemberReferalsHasProductCount($member);
        
        foreach ($bonusPricingValues as $value) {
            if ($actualSalesCount >= $value->getSalesCount()) {
                $totalReferalsPaidSum = $this->memberManager->getMemberReferalsHasProductPaidSum($member);
                $memberBaseBonus = [
                   
                    'referalsPaidSum' => $totalReferalsPaidSum,
                    'totalReferalsPaidSum' => $totalReferalsPaidSum,
                    
                    'salesCount' => $actualSalesCount,
                    'totalSalesCount' => $actualSalesCount,
                    
                    'profitValue' => $value->getProfitValue(),
                    ]
                ;
            }
        }
        return isset($memberBaseBonus)? $memberBaseBonus : null;
    }
}
