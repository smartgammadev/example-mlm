<?php
namespace Success\EventBundle\Service;

//use Gamma\Framework\Service\Service;
use Success\EventBundle\Entity\BaseEvent;
use Success\EventBundle\Entity\WebinarEvent;
use Success\MemberBundle\Entity\Member;
use Success\EventBundle\Entity\EventSignUp;
use Success\EventBundle\Entity\EventRepeat;
use Success\NotificationBundle\Service\NotificationManager;
use Success\SettingsBundle\Service\SettingsManager;
use Success\PlaceholderBundle\Service\PlaceholderManager;

class EventManager //extends Service
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    const OPEN_ACCESS_TYPE_FLAG = 'открытый';
    
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

    public function setNotificationManager(NotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;    
    }
    
    public function setSettingsManager(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }
    
    public function setPlaceholderManager(PlaceholderManager $placeholderManager)
    {
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
    
    /**
     * 
     * @param \DateTime $nowDate
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return Array
     */
    
    private function appendRepeatsForEvents(\DateTime $nowDate, \DateTime $startDate, \DateTime $endDate)
    {        
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        
        //$repeatableEvents = $repo->findAllWithActiveRepeats($nowDate);
        $repeatableEvents = $repo->findAllWithActiveRepeats($startDate);
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
    public function signUpMemberForEvent(Member $memberSignedUp, BaseEvent $event, \DateTime $signUpDateTime, $notifyUserBeforeEvent)
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
    public function generateExternalLinkForWebinarEvent(WebinarEvent $event){
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
     * @return BaseEvent[] 
     */  
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
            $eventRepeatEnd = $eventRepeat->getEndDateTime();
            if ($eventRepeatEnd->getTimestamp() < $startDate->getTimestamp()){
                return $this->getDatesForRepeat($eventRepeat, $event->getStartDateTime(), $startDate, $eventRepeatEnd);
            } else {
                return $this->getDatesForRepeat($eventRepeat, $event->getStartDateTime(), $startDate, $endDate);
            }            
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
    
    /**
     * @param BaseEvent $event
     * @return boolean
     */
    public function getEventAccessForUser(BaseEvent $event)
    {
        $isOpenEvent = ($event->getAccessType()->getName() == self::OPEN_ACCESS_TYPE_FLAG);
        $placeholders = $this->placeholderManager->getPlaceholdersFromSession();
        
        if (!$isOpenEvent){
            if (!isset($placeholders['user_businessLinkFull'])||($placeholders['user_businessLinkFull']=='')){
                return false;
            }
        }
        return true;
    }
}