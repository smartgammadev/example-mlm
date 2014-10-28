<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;

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

      $event = $this->get('success.event.event_manager')->getEventById($eventId);
      
        $em = $this->getDoctrine()->getManager();
        $config = $em -> getRepository('AiselConfigBundle:Config')->getAllSettings()['config_homepage'];
                         
        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }
        
        return array('event' => $event, 'config' => json_decode($config));
    }
}
