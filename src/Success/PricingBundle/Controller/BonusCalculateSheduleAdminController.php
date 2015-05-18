<?php

namespace Success\PricingBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class BonusCalculateSheduleAdminController extends BaseController
{
    const BONUS_COMPLETE_MESSAGE = 'Бонус партнерам начислен';
    
    /**
     * @var \Doctrine\ORM\EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;
    
    /**
     * @var \Success\PricingBundle\Service\BonusCalculator
     * @DI\Inject("success.pricing.bonus_calculator")
     */
    private $bonusCalculator;

    public function resultAction(Request $request)
    {
        $calculationId = $request->get('id');
        $calculation = $this->em->getRepository('SuccessPricingBundle:BonusCalculateShedule')->findOneBy(['id' => $calculationId]);
        return $this->render('SuccessPricingBundle:Sonata:bonus_calculation_result.html.twig', [
            'object' => $calculation,
            'action' => 'result',
        ]);
    }
    
    public function approveAction(Request $request)
    {
        $calculationId = $request->get('id');
        $calculation = $this->em->getRepository('SuccessPricingBundle:BonusCalculateShedule')->findOneBy(['id' => $calculationId]);

        $this->bonusCalculator->approveBonusCalculation($calculation);
        
        return $this->render('SuccessPricingBundle:Sonata:bonus_calculation_approve.html.twig', [
            'message' => self::BONUS_COMPLETE_MESSAGE,
            'action' => 'approve',
        ]);
    }
}
