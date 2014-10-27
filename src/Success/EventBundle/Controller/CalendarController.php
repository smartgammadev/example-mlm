<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
    /**
     * @Route("/show", name="show_calendar")
     * @Template()
     */
    public function showAction()
    {
        return array();
    }
        
    /**
     * @Route("/event/{eventId}", name="show_calendar_event", requirements={"eventId"="\d+"})
     * @Template()
     */    
    public function eventAction($eventId){
        $em = $this->getDoctrine()->getManager();
        $event = $em ->getRepository('SuccessEventBundle:BaseEvent')
                ->find($eventId);        

        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }
        return array('event' => $event);
    }
}
