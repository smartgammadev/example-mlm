<?php
namespace Success\EventBundle\Service;

//use Gamma\Framework\Service\Service;
use Success\EventBundle\Entity\BaseEvent;
use Success\EventBundle\Entity\WebinarEvent;
use Success\MemberBundle\Entity\Member;
use Success\EventBundle\Entity\EventSignUp;
use Success\EventBundle\Entity\EventRepeat;

class EventManager //extends Service
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     *
     * @var \Success\NotificationBundle\Service\NotificationManager
     */
    private $notificationManager;
    
    /**
     *
     * @var \Success\SettingsBundle\Service\SettingsManager
     */
    private $settingsManager;
    
        
    /**
     *
     * @var \Success\PlaceholderBundle\Service\PlaceholderManager
     */
    private $placeholderManager;
    
    public function __construct($notificationManager, $settingsManager, $placeholderManager)
    {
        $this->notificationManager = $notificationManager;
        $this->settingsManager = $settingsManager;
        $this->placeholderManager = $placeholderManager;
    }


    /**
     * @param Datetime $startDate 
     * @param Datetime $endDate
     * @return \Success\EventBundle\Entity\BaseEvent[]
     */  
    public function getEventsByDateRange(\DateTime $startDate, \DateTime $endDate)
    {     
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        //$result = $repo->findAllBetweenDates($startDate,$endDate);
        
        $nowDate = new \DateTime();
        
        $result = array_merge($repo->findAllBetweenDates($startDate,$endDate), $this->appendRepeatsForEvents($nowDate, $startDate, $endDate));
        
        usort($result, function($a, $b)
            {
                return strcmp($a->getStartDateTime()->format('Y-m-d H:i:s'), $b->getStartDateTime()->format('Y-m-d H:i:s'));
            });        
        return $result;
    }
    
    
    private function appendRepeatsForEvents(\DateTime $nowDate, \DateTime $startDate, \DateTime $endDate)
    {
        
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        
        $repeatableEvents = $repo->findAllWithActiveRepeats($nowDate);
        $result = [];
        
        foreach ($repeatableEvents as $repeatableEvent){
            
            $repeatDates = $this->getRepeatsForEvent($repeatableEvent, $startDate, $endDate);
            $repeatDays = $repeatableEvent->getEventRepeat()->getRepeatDays();
            
                foreach ($repeatDates as $repeatDate) {
                    $repeatDayOfWeek = date('w', $repeatDate->getTimestamp());
                    if (isset($repeatDays[intval($repeatDayOfWeek)])&&
                             ($repeatDays[intval($repeatDayOfWeek)])){
                            $eventClone = clone $repeatableEvent;
                            $eventClone->setStartDateTime($repeatDate);
                            $result[] = $eventClone;
                    }                    
                }
        }
        return $result;        
    }
    
    /**
     * @param Datetime $startDate
     * @return \Success\EventBundle\Entity\BaseEvent
     */
    public function getNearestNextEvent($startDate)
    {
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->findNextNearestByDate($startDate);
    }

    public function getEventsForDate($startDate)
    {
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        $result = $repo->findAllByDate($startDate);
        return $result;
    }
    
    public function getNextEventsForDate($startDate)
    {
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->findNextByDate($startDate);
    }

    
    public function getAllEventsOfWeekByDate($dateOfWeek)
    {
        $startDate = $this->firstDayOfWeek($dateOfWeek);
        $endDate = $this->lastDayOfWeek($dateOfWeek);
        
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");        
        return $repo->findAllBetweenDates($startDate, $endDate);
    }
    
    public function getNextEventsOfWeekByDate($dateOfWeek)
    {
        $endDate = $this->lastDayOfWeek($dateOfWeek);
        
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");        
        return $repo->findAllBetweenDates($dateOfWeek, $endDate);        
    }
    
    /**
     * @param $eventId
     * @return \Success\EventBundle\Entity\BaseEvent
     */  
    public function getEventById($eventId)
    {
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");        
        $event = $repo->findOneBy(array('id' => $eventId));
        return $event;
    }
    
    /**
     * 
     * @param \Success\MemberBundle\Entity\Member $member
     * @param BaseEvent $event
     * @param \DateTime $signUpDate
     * @return boolean True if already exists, false if created
     */
    private function resolveSignUpForMember(Member $member, BaseEvent $event, \DateTime $signUpDate)
    {
        $repo = $this->em->getRepository('SuccessEventBundle:EventSignUp');        
        $existingSignUp = $repo->findOneBy(array('member' => $member->getId(),'event' => $event->getId()));        
        
        if (!$existingSignUp){
            $signUp = new EventSignUp();
            $signUp->setMember($member);
            $signUp->setEvent($event);
            $signUp->setSignUpDateTime($signUpDate);
            $this->em->persist($signUp);
            $this->em->flush();            
            return false;
        }
        return true;
    }    
    
    /**
     * @param \Success\MemberBundle\Entity\Member $memberSignedUp
     * @param BaseEvent $event
     * @param \DateTime $signUpDateTime
     * @param boolean $notifyUserBeforeEvent
     * @return boolean True if already exists, false if created
     */        
    public function SignUpMemberForEvent(Member $memberSignedUp, BaseEvent $event, \DateTime $signUpDateTime, $notifyUserBeforeEvent)
    {
       
       $placeholders = $this->placeholderManager->getPlaceholdersFromSession();       
       $alreadyExists = $this->resolveSignUpForMember($memberSignedUp, $event, $signUpDateTime);
       
       if (!$alreadyExists){
            if (isset($placeholders['sponsor_email'])){
                $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['sponsor_email'], 'sponsorSignUpEmailMessage');
            }
            if (isset($placeholders['sponsor_phone'])){
                $this->notificationManager->CreateSMSNotification($signUpDateTime, $placeholders['sponsor_phone'], 'sponsorSignUpSMSMessage');
            }
            if (isset($placeholders['user_email'])){
                $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['user_email'], 'userSignUpEmailMessage');
            }
            if ($notifyUserBeforeEvent){
                $minutesBeforeEvent = $this->settingsManager->getSettingValue('beforeEventDateModifier');
                $datetimeBeforeEvent = $event->getStartDateTime();
                $datetimeBeforeEvent->modify('-'.$minutesBeforeEvent.' minutes');
                
                if (isset($placeholders['user_email'])){
                    $this->notificationManager->CreateEmailNotification($datetimeBeforeEvent, $placeholders['user_email'], 'userBeforeEventEmailMessage');
                }
                
                if (isset($placeholders['user_phone'])){
                    $this->notificationManager->CreateSMSNotification($datetimeBeforeEvent, $placeholders['user_phone'], 'userBeforeEventSMSMessage');
                }
            }
       }
       return $alreadyExists;
    }
    /**
     * @param BaseEvent $event
     * @return string Url
     */
    public function GenerateExternalLinkForWebinarEvent(WebinarEvent $event){
        $placeholders = $this->placeholderManager->getPlaceholdersFromSession();
        $url = $event->getUrl().'/';
        
        if (isset($placeholders['user_first_name'])){
            $url .= urlencode($placeholders['user_first_name']);            
            $url .= urlencode(' от '.$placeholders['sponsor_first_name'].' '.$placeholders['sponsor_last_name']);
        }

        $pwd = $event->getPassword();
            
        if (!(($pwd=='')||($pwd==null))){
            $url .= '/'.md5($pwd);
            }
        return $url;
    }
    
    
    /**
     * @param \DateTime $dateOfWeek
     * @return \DateTime first day of week where $dateOfWeek in
     */
    private function firstDayOfWeek(\DateTime $dateOfWeek)
    {
        $thisDate = new \DateTime();
        $thisDate->setTimestamp($dateOfWeek->getTimestamp());
        $thisDay = strftime("%u", $thisDate->getTimestamp());                
        $firstDayOfWeekTimestamp = $thisDate->modify('-'.($thisDay-1).' days')->getTimestamp();        
        $firstDateOfWeek = new \DateTime();
        $firstDateOfWeek->setTimestamp(mktime(0, 0, 0, date("m", $firstDayOfWeekTimestamp) , date("d", $firstDayOfWeekTimestamp), date("Y", $firstDayOfWeekTimestamp)));        
        //echo $firstDateOfWeek->format('Y-m-d H:i:s');        
        return $firstDateOfWeek;
    }

    /**
     * @param \DateTime $dateOfWeek
     * @return \DateTime last day of week where $dateOfWeek in
     */    
    public function lastDayOfWeek(\DateTime $dateOfWeek)
    {
        $thisDate = new \DateTime();
        $thisDate->setTimestamp($dateOfWeek->getTimestamp());
        $thisDay = strftime("%u", $thisDate->getTimestamp());                
        $lastDayOfWeekTimestamp = $thisDate->modify('+'.(7-$thisDay).' days')->getTimestamp();        
        $lastDateOfWeek = new \DateTime();
        $lastDateOfWeek->setTimestamp(mktime(23, 59, 59, date("m", $lastDayOfWeekTimestamp) , date("d", $lastDayOfWeekTimestamp), date("Y", $lastDayOfWeekTimestamp)));
        //echo $lastDateOfWeek->format('Y-m-d H:i:s');
        return $lastDateOfWeek;
    }
    
    
    
    public function getAllRepatableEvents()
    {
        $repo = $this->em->getRepository('SuccessEventBundle:BaseEvent');
        $now = new \DateTime();
        return $repo->findAllWithActiveRepeats($now);
    }
    
    
    /**
     * @param BaseEvent $event
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array Description
     */
    public function getRepeatsForEvent(BaseEvent $event, \DateTime $startDate, \DateTime $endDate)
    {        
        
        if ($event->getEventRepeat() == null){
            return null;
            
        } else {
            
            $eventRepeat = $event->getEventRepeat();            
            
            return $this->getDatesForRepeat($eventRepeat, $event->getStartDateTime(), $startDate, $endDate);
        }
    }
    
    /**
     * 
     * @param EventRepeat $eventRepeat
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array of dates
     */    
    private function getDatesForRepeat(EventRepeat $eventRepeat, \DateTime $eventStartDate, \DateTime $startDate, \DateTime $endDate)
    {
//        if ($eventRepeat->getEndDateTime()->getTimestamp() < $endDate->getTimestamp()){
//            return null;            
//        }           
        $repeatType = $eventRepeat->getRepeatType();        
        $interval = $eventRepeat->getRepeatInterval();                
        $datesInterval = new \DateInterval('P'.$interval.$repeatType);
        $repeatDates = $this->getDatesForInterval($datesInterval, $eventStartDate, $startDate, $endDate);
        return $repeatDates;
    }
    
    /**
     * 
     * @param \DateInterval $inteval
     * @param \DateTime $eventStartDate
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array of DateTime objects
     */    
    private function getDatesForInterval(\DateInterval $inteval, \DateTime $eventStartDate, \DateTime $startDate, \DateTime $endDate)
    {
        $result = [];
        $daterange = new \DatePeriod($eventStartDate, $inteval ,$endDate);
            foreach($daterange as $date){
                if ($date->getTimestamp()>=$startDate->getTimestamp()){                    
                    $result[] = $date;
                }                    
            }
        return $result;
    }
}