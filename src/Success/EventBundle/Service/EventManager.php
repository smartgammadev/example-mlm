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
     * @param Member $member
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
     * @param Member $memberSignedUp
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
            $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['sponsor_email'], 'sponsor sign up email notification');
            $this->notificationManager->CreateSMSNotification($signUpDateTime, $placeholders['sponsor_phone'], 'sponsor sign up sms notification');
            $this->notificationManager->CreateEmailNotification($signUpDateTime, $placeholders['user_email'], 'user sign up email notification');

            if ($notifyUserBeforeEvent){
                $minutesBeforeEvent = $this->settingsManager->getSettingValue('beforeEventDateModifier');                
                $datetimeBeforeEvent = $event->getStartDateTime()->modify('-'.$minutesBeforeEvent.' minutes');
                $this->notificationManager->CreateEmailNotification($datetimeBeforeEvent, $placeholders['user_email'], 'user before event email notification');
                $this->notificationManager->CreateSMSNotification($datetimeBeforeEvent, $placeholders['user_phone'], 'user before event sms notification');
            }
       }
       return $alreadyExists;
    }
    /**
     * @param BaseEvent $event
     * @return string Url
     */
    public function GenerateExternalLinkForWebinarEvent(WebinarEvent $event){
        $placeholders = $this->placeholderManager->getPlaceholdersValuesForExternalLink();        
        $url = $event->getUrl().'?';        
        foreach ($placeholders as $ph){
            $url .= $ph['placeholder']->getFullPattern().'='.$ph['value'].'&';
        }
        return $url;
    }
}