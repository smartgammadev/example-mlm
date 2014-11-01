<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @Route("/calendarevents")
 */
class CalendarController extends Controller
{   //pm-di
    
    /**
     * @DI\Inject("success.event.event_manager")
     */
    private $eventManager;
    
    /**
     * @DI\Inject("success.settings.settings_manager")
     */    
    private $settingsManager;
    
    /**
     *
     * @DI\Inject("success.placeholder.placeholder_manager")
     */    
    private $placeholderManager;
    
    /**
     * @Route("/{template}/{slug}", name="show_calendar")
     * @Template()
     */
    public function showAction(Request $request)
    { //pm->assingFoundPHTosession($request->query->all())
        $this->placeholderManager->assignPlaceholdersToSession($request->query->all());
        //$request->query->all();
        return array();
    }

    /**
     * @Route("/{template}/event/{eventId}", name="show_calendar_event", requirements={"eventId"="\d+"})
     * @Template()
     */
    public function eventAction($eventId){
      
      
      $event = $this->eventManager->getEventById($eventId);      
      $minutesToVisitEvent = $this->settingsManager->getSettingValue('minutesToVisitEvent');

      $now = new \DateTime('now');
      $allowVisitEvent =  ($event->getStartDateTime()->getTimestamp() - $now->getTimestamp() < $minutesToVisitEvent*60);
      $isPastEvent = $event->getStartDateTime()->getTimestamp() < $now->getTimestamp();

        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }
        return array('event' => $event, 'allowVisitEvent' => $allowVisitEvent, 'isPastEvent' => $isPastEvent );
    }
}