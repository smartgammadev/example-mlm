<?php

namespace Success\EventBundle\Service;

//use Gamma\Framework\Service\Service;

class EventManager //extends Service
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     * @param Datetime $startDate 
     * @param Datetime $endDate
     * @return \Success\EventBundle\Entity\BaseEvent[]
     */  
    public function getEventsByDateRange($startDate, $endDate)
    {     
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->findAllBetweenDates($startDate,$endDate);
    }
    
    /**
     * @param $eventId
     * @return \Success\EventBundle\Entity\BaseEvent
     */  
    public function getEventById($eventId)
    {
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->find($eventId);
    }
}
