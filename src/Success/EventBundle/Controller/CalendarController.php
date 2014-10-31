<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Success\SettingsBundle\SuccessSettingsBundle;

/**
 * @Route("/calendarevents")
 */
class CalendarController extends Controller
{   //pm-di
   
    /**
     * @Route("/{template}/{slug}", name="show_calendar", requirements={"placeholders"=".+"})
     * @Template()
     */
    public function showAction()
    { //pm->assingFoundPHTosession($request->query->all())
        
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
      $isPastEvent = $event->getStartDateTime()->getTimestamp() < $now->getTimestamp();

        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }
        return array('event' => $event, 'allowVisitEvent' => $allowVisitEvent, 'isPastEvent' => $isPastEvent );
    }
}