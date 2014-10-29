<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Success\SettingsBundle\SuccessSettingsBundle;

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
      $minutesToVisitEvent = $this->get('success.settings.settings_manager')->getSettingValue('minutesToVisitEvent');

      $now = new \DateTime('now');      
      $allowVisitEvent =  ($event->getStartDateTime()->getTimestamp() - $now->getTimestamp() < $minutesToVisitEvent*60);
      

        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }

        return array('event' => $event, 'allowVisitEvent' => $allowVisitEvent);
    }
}
