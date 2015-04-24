<?php
namespace Success\PricingBundle\Service;

use Success\PricingBundle\Entity\Pricing;
use Success\PricingBundle\Entity\PricingValue;

class PricingManager
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     * @param integer $levelsUp
     */
    public function createNewPricing($name, $levelsUp = 3, $isAbsoluteValue = true, $profitValues = [4, 3, 2])
    {
        $newPricing = new Pricing();
        $newPricing->setName($name);
        $newPricing->setLevelsUp($levelsUp);
        $this->em->persist($newPricing);
        
        for ($level = 0; $level <= $levelsUp - 1; $level++) {
            $newPricingValue = new PricingValue();
            $newPricingValue->setIsAbsoluteValue($isAbsoluteValue);
            $newPricingValue->setLevel($level);
            $newPricingValue->setProfitValue($profitValues[$level]);
            $newPricingValue->setPricing($newPricing);
            $this->em->persist($newPricingValue);
        }
        $this->em->flush();
        return $newPricing;
    }
    
    public function getCurrentPricing()
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:Pricing');
        return $repo->findCurrentPricing();
    }
}
