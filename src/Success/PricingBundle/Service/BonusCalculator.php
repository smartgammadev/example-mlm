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
     * @param Member $member
     * @return type
     */
    public function calculateBonusForMember(Member $member, DateRange $dateRange = null)
    {
        $memberBaseBonus = $this->calculateMemberBaseBonus($member, $dateRange);
        if (!isset($memberBaseBonus)) {
            return null;
        }
        $memberReferals = $this->memberManager->getMemberFirstReferalsHasProduct($member, $dateRange);
        foreach ($memberReferals as $memberReferal) {
            $memberReferalBaseBonus = $this->calculateBonusForMember($memberReferal, $dateRange);
            if ($memberReferalBaseBonus) {
                $memberBaseBonus = $this->getBonusesDiffs($memberBaseBonus, $memberReferalBaseBonus);
            }
        }
        return $memberBaseBonus;
    }

    /**
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
            'profitValue' => $bonus1['profitValue'], // - $bonus2['profitValue'],
            'add' => $additionalBonuses,
        ];
        return $result;
    }

    /**
     * @param Member $member
     * @return type
     */
    public function calculateMemberBaseBonus(Member $member, DateRange $dateRange = null)
    {
        $bonusPricing = $this->bonusPricingManager->getCurrentBonusPricing();
        $bonusPricingValues = $bonusPricing->getPricingValues();
        $actualSalesCount = $this->memberManager->getMemberReferalsHasProductCount($member, null, $dateRange);

        foreach ($bonusPricingValues as $value) {
            if ($actualSalesCount >= $value->getSalesCount()) {
                $totalReferalsPaidSum = $this->memberManager->getMemberReferalsHasProductPaidSum($member, $dateRange);
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
        return isset($memberBaseBonus) ? $memberBaseBonus : null;
    }
}
