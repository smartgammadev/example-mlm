<?php

namespace Success\PricingBundle\Service;

use Success\PricingBundle\Entity\BonusPricing;
use Success\PricingBundle\Entity\BonusPricingValue;

class BonusPricingManager
{

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

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
}
