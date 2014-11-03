<?php

namespace Success\EventBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;


class CalendarEventListener 
{
    private $eventManager;

    public function __construct(\Success\EventBundle\Service\EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();
        
        // The original request so you can get filters from the calendar
        // Use the filter in your query for example
        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');

        // load events using your custom logic here,
        // for instance, retrieving events from a repository
        $cEvents = $this->eventManager->getEventsByDateRange($startDate, $endDate);
        $this->addEventToCalendar($cEvents, $calendarEvent);
    }
    
    /**
     * @param array $cEvents
     * @param CalendarEvent $calendarEvent
     * @return void
     */
    private function addEventToCalendar(array $cEvents, CalendarEvent $calendarEvent)
    {
        foreach($cEvents as $cEvent) {
               
            $eventEntity = new EventEntity($cEvent->getName(), $cEvent->getStartDateTime(), null, true);
            
            if ($cEvent instanceof \Success\EventBundle\Entity\WebinarEvent){
                $eventEntity->setBgColor('#FF5D5E');
            } else {
                $eventEntity->setBgColor('#0000ff');
            }
            
            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event            
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl('/calendarevents/calendar/event/'.$cEvent->getId().'/signup');
            //$eventEntity->setCssClass('event-detail');
            $calendarEvent->addEvent($eventEntity);
        }        
    }
    
    
}
