<?php

namespace Success\PricingBundle\Traits;

use Success\PricingBundle\Service\BonusPricingManager;

trait BonusPricingManagerTrait
{

    /**
     * @var $productPricingManager \Success\PricingBundle\Service\ProductPricingManager
     */
    private $bonusPricingManager;

    public function setBonusPricingManager(BonusPricingManager $bonusPricingManager)
    {
        $this->bonusPricingManager = $bonusPricingManager;
    }
}
