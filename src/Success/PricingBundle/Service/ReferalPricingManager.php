<?php
namespace Success\PricingBundle\Service;

use Success\PricingBundle\Entity\ReferalPricing;
use Success\PricingBundle\Entity\ReferalPricingValue;
use Success\MemberBundle\Entity\Member;

class ReferalPricingManager
{
    const DEFAULT_REFERAL_PRICING_NAME = 'default';
    const DEFAULT_REFERAL_PRICING_VALUE_ISABSOLUTE = true;
    const DEFAULT_REFERAL_PRICING_VALUE_PROFIT = 1;
    const REFERAL_ACCOUNT_OPERATION_TAG = 'реф. выплата за покупку пакета рефералом: партнер - %s, поколение - %s';

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    use \Success\TreasureBundle\Traits\SetAccountManagerTrait;
    use \Success\MemberBundle\Traits\SetMemberManagerTrait;
    
    /**
     * @param type $name
     * @param type $levelsUp
     * @param type $isAbsoluteValue
     * @param type $profitValues
     * @return ReferalPricing
     */
    private function createDefaultReferalPricing($name, $levelsUp = 3, $isAbsoluteValue = true, $profitValues = [4, 3, 2])
    {
        $newReferalPricing = new ReferalPricing();
        $newReferalPricing->setName($name);
        $newReferalPricing->setLevelsUp($levelsUp);
        $this->em->persist($newReferalPricing);
        
        for ($level = 0; $level <= $levelsUp - 1; $level++) {
            $newReferalPricingValue = new ReferalPricingValue();
            $newReferalPricingValue->setIsAbsoluteValue($isAbsoluteValue);
            $newReferalPricingValue->setLevel($level);
            $newReferalPricingValue->setProfitValue($profitValues[$level]);
            $newReferalPricingValue->setReferalPricing($newReferalPricing);
            $this->em->persist($newReferalPricingValue);
        }
        $this->em->flush();
        return $newReferalPricing;
    }
    
    
    /**
     * @return ReferalPricing
     */
    public function getCurrentReferalPricing()
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:ReferalPricing');
        $currentReferalPricing = $repo->findCurrentReferalPricing();
        if (!$currentReferalPricing) {
            $currentReferalPricing = $this->createDefaultReferalPricing(self::DEFAULT_REFERAL_PRICING_NAME);
        }
        return $currentReferalPricing;
    }
    
    /**
     * @return ReferalPricing
     */
    public function copyReferalPricingFromCurrent()
    {
        /**
         * @var \Success\PricingBundle\Entity\ReferalPricing $source
         */
        $source = $this->getCurrentReferalPricing();
        $result =  new ReferalPricing();
        $result->setLevelsUp($source->getLevelsUp());
        $result->setName($source->getName());
        
        foreach ($source->getPricingValues() as $pricingValue) {
            $newPricingValue = new ReferalPricingValue();
            $newPricingValue->setIsAbsoluteValue($pricingValue->getIsAbsoluteValue());
            $newPricingValue->setLevel($pricingValue->getLevel());
            $newPricingValue->setProfitValue($pricingValue->getProfitValue());
            $newPricingValue->setReferalPricing($result);
            $result->addPricingValue($newPricingValue);
        }
        return $result;
    }
    
    /**
     * @param ReferalPricing $referalPricing
     * @param integrer $countOfLevelsToAdd
     */
    public function addLevelsToReferalPricing(ReferalPricing $referalPricing, $countOfLevelsToAdd)
    {
        for ($level = 0; $level <= $countOfLevelsToAdd-1; $level++) {
            $newPricingValue = new ReferalPricingValue();
            $newPricingValue->setIsAbsoluteValue(self::DEFAULT_REFERAL_PRICING_VALUE_ISABSOLUTE);
            $newPricingValue->setProfitValue(self::DEFAULT_REFERAL_PRICING_VALUE_PROFIT);
            $newPricingValue->setReferalPricing($referalPricing);
            $referalPricing->addPricingValue($newPricingValue);
            $newPricingValue->setLevel($referalPricing->getPricingValues()->count()-1);
            $this->em->persist($newPricingValue);
        }
        $this->em->flush();
        
        return $referalPricing;
    }
    
    public function removeLevelsFromReferalPricing(ReferalPricing $referalPricing, $countOfLevelsToRemove)
    {
        for ($level = 0; $level <= $countOfLevelsToRemove-1; $level++) {
            $referalPricing->removePricingValue($referalPricing->getPricingValues()->last());
        }
        $this->em->flush();
        return $referalPricing;
    }
    
    /**
     * @param Member $member
     * @param decimal $baseValue
     */
    public function processReferalPricingForMember(Member $member, $baseValue)
    {
        $referalPricing = $this->getCurrentReferalPricing();
        foreach ($referalPricing->getPricingValues() as $pricingValue) {
            $profitAmount = $this->calculateReferalPricingProfit(
                $pricingValue->getIsAbsoluteValue(),
                $pricingValue->getProfitValue(),
                $baseValue
            );
            $level = $pricingValue->getLevel();
            $sponsor = $this->memberManager->getMemberSponsorOfLevel($member, $level);
            if ($sponsor) {
                $tagMessage = sprintf(self::REFERAL_ACCOUNT_OPERATION_TAG, $member->getExternalId(), $level+1);
                $this->accountManager->doAccountOperation($sponsor, $profitAmount, $tagMessage);
            }
        }
    }
    
    private function calculateReferalPricingProfit($isAbsolute, $profitValue, $base)
    {
        if ($isAbsolute) {
            return $profitValue;
        }
        return ($base/100)*$profitValue;
    }
}
