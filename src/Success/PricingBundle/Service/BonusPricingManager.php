<?php

namespace Success\PricingBundle\Service;

use Success\PricingBundle\Entity\BonusPricing;
use Success\PricingBundle\Entity\BonusPricingValue;
use Success\MemberBundle\Entity\Member;

class BonusPricingManager
{

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    use \Success\MemberBundle\Traits\SetMemberManagerTrait;

    const DEFAULT_BONUS_PRICING_NAME = 'default';

    private static $DEFAULT_BONUS_PRICING_VALUES = [
        ['salesCount' => 100, 'profitValue' => 1],
        ['salesCount' => 300, 'profitValue' => 2],
        ['salesCount' => 500, 'profitValue' => 5],
    ];

    private function createDefaultBonusPricing()
    {
        $dateCreated = new \DateTime();
        $newBonusPricing = new BonusPricing();
        $newBonusPricing->setPricingName(self::DEFAULT_BONUS_PRICING_NAME);
        $newBonusPricing->setCreated($dateCreated);
        $this->em->persist($newBonusPricing);

        foreach (self::$DEFAULT_BONUS_PRICING_VALUES as $value) {
            $newBonusPricingValue = new BonusPricingValue();
            $newBonusPricingValue->setPricing($newBonusPricing);
            $newBonusPricingValue->setSalesCount($value['salesCount']);
            $newBonusPricingValue->setProfitValue($value['profitValue']);
            $this->em->persist($newBonusPricingValue);
        }
        $this->em->flush();

        return $newBonusPricing;
    }

    /**
     * @return ReferalPricing
     */
    public function getCurrentBonusPricing()
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:BonusPricing');
        $currentBonusPricing = $repo->findCurrentBonusPricing();
        if (!$currentBonusPricing) {
            $currentBonusPricing = $this->createDefaultBonusPricing();
        }
        return $currentBonusPricing;
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
