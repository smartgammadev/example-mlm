<?php
namespace Success\EventBundle\Service;

//use Gamma\Framework\Service\Service;
use Success\EventBundle\Entity\BaseEvent;
use Success\EventBundle\Entity\WebinarEvent;
use Success\MemberBundle\Entity\Member;
use Success\EventBundle\Entity\EventSignUp;

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
    public function getEventsByDateRange($startDate, $endDate)
    {     
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->findAllBetweenDates($startDate,$endDate);
    }
    
    /**
     * @param Datetime $startdate
     * @return \Success\EventBundle\Entity\BaseEvent
     */
    public function getNearestNextEvent($startDate)
    {
        /* @var $repo \Success\EventBundle\Entity\BaseEventRepository */
        $repo = $this->em->getRepository("SuccessEventBundle:BaseEvent");
        return $repo->findNextNearestByDate($startDate);
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
        return $repo->find($eventId);
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
            $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['sponsor_email'], 'sponsorSignUpEmailMessage');
            $this->notificationManager->CreateSMSNotification($signUpDateTime, $placeholders['sponsor_phone'], 'sponsorSignUpSMSMessage');
            $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['user_email'], 'userSignUpEmailMessage');

            if ($notifyUserBeforeEvent){
                $minutesBeforeEvent = $this->settingsManager->getSettingValue('beforeEventDateModifier');                
                $datetimeBeforeEvent = $event->getStartDateTime()->modify('-'.$minutesBeforeEvent.' minutes');
                $this->notificationManager->CreateEmailNotification($datetimeBeforeEvent, $placeholders['user_email'], 'userBeforeEventEmailNotification');
                $this->notificationManager->CreateSMSNotification($datetimeBeforeEvent, $placeholders['user_phone'], 'userBeforeEventSMSNotification');
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
        $url = $url.urlencode($placeholders['user_first_name'].' '.$placeholders['user_last_name']);        
        $pwd = $event->getPassword();
        if (!(($pwd=='')||($pwd==null))){
            $url = $url.'/'.md5($pwd);
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
    private function lastDayOfWeek(\DateTime $dateOfWeek)
    {
        $thisDate = new \DateTime();
        $thisDate->setTimestamp($dateOfWeek->getTimestamp());
        $thisDay = strftime("%u", $thisDate->getTimestamp());                
        $lastDayOfWeekTimestamp = $thisDate->modify('+'.(7-$thisDay).' days')->getTimestamp();        
        $lastDateOfWeek = new \DateTime();
        $lastDateOfWeek->setTimestamp(mktime(12, 59, 59, date("m", $lastDayOfWeekTimestamp) , date("d", $lastDayOfWeekTimestamp), date("Y", $lastDayOfWeekTimestamp)));
        //echo $lastDateOfWeek->format('Y-m-d H:i:s');
        return $lastDateOfWeek;
    }

}