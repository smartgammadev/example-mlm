<?php

namespace Success\PricingBundle\Traits;

use Success\PricingBundle\Service\BonusPricingManager;

trait BonusPricingManagerTrait
{

    /**
     * @var $bonusPricingManager \Success\PricingBundle\Service\BonusPricingManager
     */
    private $bonusPricingManager;

    public function setBonusPricingManager(BonusPricingManager $bonusPricingManager)
    {
        $this->bonusPricingManager = $bonusPricingManager;
    }
}
