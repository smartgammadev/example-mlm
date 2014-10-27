<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
    /**
     * @Route("/show")
     * 
     */
    public function showAction()
    {
        return $this->render('SuccessEventBundle:Calendar:show.html.twig', array());
    }
    
    public function eventAction($id){
        
    }
}
