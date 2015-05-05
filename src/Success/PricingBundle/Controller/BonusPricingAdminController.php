<?php

namespace Success\PricingBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Success\PricingBundle\Service\BonusPricingManager;
use JMS\DiExtraBundle\Annotation as DI;

class BonusPricingAdminController extends BaseController
{

    /**
     * @var \Success\PricingBundle\Service\BonusPricingManager $bonusPricingManager
     * @DI\Inject("success.pricing.bonus_pricing_manager")
     */
    private $bonusPricingManager;
    
    public function listAction()
    {
        return $this->redirectToRoute(
            'admin_success_pricing_bonuspricing_edit',
            ['id' => $this->bonusPricingManager->getCurrentBonusPricing()->getId()]
        );
    }
}
