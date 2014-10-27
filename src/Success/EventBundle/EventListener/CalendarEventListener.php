<?php

namespace Success\EventBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;

class CalendarEventListener {

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $cEvents = $this->entityManager->getRepository('SuccessEventBundle:BaseEvent')
                ->findAllBetweenDates($startDate,$endDate);
        
        foreach($cEvents as $cEvent) {
               
            $eventEntity = new EventEntity($cEvent->getName(), $cEvent->getStartDateTime(), null, true);
            
            if ($cEvent instanceof \Success\EventBundle\Entity\WebinarEvent){
                $eventEntity->setBgColor('#0000FF');
            } else {
                $eventEntity->setBgColor('#FF0000');
            }
            
            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event            
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl('/calendar/event/'.$cEvent->getId());
            $eventEntity->setCssClass('event-detail');
            $calendarEvent->addEvent($eventEntity);
        }
    }
    
    
}
