<?php

namespace Success\PricingBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class BonusCalculateSheduleAdminController extends BaseController
{
    
    /**
     * @var \Doctrine\ORM\EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;
    
    
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
        $this->getCsrfToken();
        return $this->render('SuccessPricingBundle:Sonata:bonus_calculation_result.html.twig', [
            'action' => 'approve',
            'csrf_token' => $this->getCsrfToken('test')
        ]);
    }
}
