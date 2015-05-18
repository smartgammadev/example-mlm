<?php

namespace Success\PricingBundle\Traits;

use Success\PricingBundle\Service\BonusCalculator;

trait BonusCalculatorTrait
{

    /**
     * @var $bonusCalculator \Success\PricingBundle\Service\BonusCalculator
     */
    private $bonusCalculator;

    public function setBonusCalculator(BonusCalculator $bonusCalculator)
    {
        $this->bonusCalculator = $bonusCalculator;
    }
}
