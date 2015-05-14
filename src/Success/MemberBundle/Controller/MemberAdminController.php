<?php

namespace Success\MemberBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Success\PricingBundle\Service\BonusPricingManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

class MemberAdminController extends BaseController
{

    /**
     * @var \Success\PricingBundle\Service\BonusPricingManager $bonusPricingManager
     * @DI\Inject("success.pricing.bonus_pricing_manager")
     */
    private $bonusPricingManager;
        
    public function calculateAction(Request $request)
    {
        $member = $this->admin->getSubject();
        $dateRange = new \Success\PricingBundle\Utils\DateRange(new \DateTime("2015-03-01"), new \DateTime());
        $bonus = $this->bonusPricingManager->calculateBonusForMember($member, $dateRange);
        
        return $this->render('SuccessMemberBundle:Sonata:bonus_calculation.html.twig', 
                array('object' => $member,
                      'action' => 'calculate',
                    'bonus' => $bonus));
    }
}
