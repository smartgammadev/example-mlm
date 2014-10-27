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
        
    /**
     * @Route("/event/{eventId}")
     * 
     */    
    public function eventAction($eventId){
        $em = $this->getDoctrine()->getManager();
        $event = $em ->getRepository('SuccessEventBundle:BaseEvent')
                ->find($eventId);        

        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }
        return $this->render('SuccessEventBundle:Calendar:event.html.twig',array('event'=>$event));
    }
}
