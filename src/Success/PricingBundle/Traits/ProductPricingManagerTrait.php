<?php

namespace Success\PricingBundle\Traits;

use Success\PricingBundle\Service\ProductPricingManager;

trait ProductPricingManagerTrait
{

    /**
     *
     * @var $productPricingManager \Success\PricingBundle\Service\ProductPricingManager
     */
    private $productPricingManager;

    public function setProductPricingManager(ProductPricingManager $productPricingManager)
    {
        $this->productPricingManager = $productPricingManager;
    }
}
