<?php

namespace Success\PricingBundle\Traits;

use Success\PricingBundle\Service\ReferalPricingManager;

trait ReferalPricingManagerTrait
{

    /**
     *
     * @var $referalPricingManager \Success\PricingBundle\Service\ReferalPricingManager
     */
    private $referalPricingManager;

    public function setReferalPricingManager(ReferalPricingManager $referalPricingManager)
    {
        $this->referalPricingManager = $referalPricingManager;
    }
}
