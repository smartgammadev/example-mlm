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

    public function calculateBonusForMember(Member $member)
    {
        $bonusPricing = $this->getCurrentBonusPricing();
        $bonusPricingValues = $bonusPricing->getPricingValues();
        $actualSalesCount = $this->memberManager->getMemberReferalsHasProductCount($member);
        
        foreach ($bonusPricingValues as $value) {
            if ($actualSalesCount >= $value->getSalesCount()) {
                $memberBaseBonus = [
                    'referalsPaidSum' => $this->memberManager->getMemberReferalsHasProductPaidSum($member),
                    'salesCount' => $actualSalesCount,
                    'profitValue' => $value->getProfitValue(),
                    ]
                ;
            }
        }
        if (!isset($memberBaseBonus)) {
            return null;
        }
        
        $memberReferals = $this->memberManager->getMemberReferalsHasProduct($member);
        
        foreach ($memberReferals as $memberReferal) {
            $memberReferalBonus = $this->calculateBonusForMember($memberReferal);
            if ($memberReferalBonus) {
                $memberBaseBonus = $this->calculateMemberBonusesDiff($memberBaseBonus, $memberReferalBonus);
                //var_dump($memberBaseBonus);
            }
        }
        
        return $memberBaseBonus;
    }
    
    private function calculateMemberBonusesDiff(array $baseBonus, array $toSubstractBonus)
    {
        $result = [
            'referalsPaidSum' => $baseBonus['referalsPaidSum'] - $toSubstractBonus['referalsPaidSum'],
            'salesCount' => $baseBonus['salesCount'] - $toSubstractBonus['salesCount'],
            'profitValue' => $baseBonus['profitValue'],
        ];
        $result['difference'][] = [
            'referalsPaidSum' => $toSubstractBonus['referalsPaidSum'],
            'salesCount' => $toSubstractBonus['salesCount'],
            'profitValue' => $baseBonus['profitValue'] - $toSubstractBonus['profitValue'],
        ];
        return $result;
    }
    
    private function calculateMemberSubBonus(array $baseBonus, array $toSubstractBonus)
    {
        $result = [
            'referalsPaidSum' => $baseBonus['referalsPaidSum'] - $toSubstractBonus['referalsPaidSum'],
            'salesCount' => $baseBonus['salesCount'] - $toSubstractBonus['salesCount'],
            'profitValue' => $baseBonus['profitValue'] - $toSubstractBonus['profitValue'],
        ];
        return $result;
    }
}
