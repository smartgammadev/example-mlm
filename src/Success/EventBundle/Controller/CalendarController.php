<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Success\EventBundle\Form\Type\SignupType;

/**
 * @Route("/calendarevents")
 */
class CalendarController extends Controller
{    
    /**
     * @DI\Inject("success.event.event_manager")
     */
    private $eventManager;
    
    /**
     * @DI\Inject("success.settings.settings_manager")
     */    
    private $settingsManager;
    
    /**
     * @DI\Inject("success.placeholder.placeholder_manager")
     */    
    private $placeholderManager;
    
    /**
     *
     * @DI\Inject("success.member.member_manager")
     */        
    private $memberManager;
    
    /**
     *
     * @DI\Inject("success.notification.notification_manager")
     */        
    private $notificationManager;
    
    /**
     * @Route("/{template}/{slug}", name="show_calendar")
     * @Template()
     */
    public function showAction(Request $request)
    { //pm->assingFoundPHTosession($request->query->all())
        $placeholders = $request->query->all();                
        $this->placeholderManager->assignPlaceholdersToSession($placeholders);
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
        return array('event' => $event, 
                     'allowVisitEvent' => $allowVisitEvent, 
                     'isPastEvent' => $isPastEvent 
                    );
    }
    
    /**
     * @Route("/{template}/event/{eventId}/signup", name="calendar_event_signup", requirements={"eventId"="\d+"})
     * @Template()
     */
    public function signupAction($eventId, Request $request) {
        /**
         * @var \Success\EventBundle\Entity\BaseEvent Event for sign up
         */
        $event = $this->eventManager->getEventById($eventId);
        
        $form = $this->createForm(new SignupType($this->placeholderManager));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $placeholders = $this->placeholderManager->getPlaceholdersFromSession();            
            $formdata = $form->getData();
            
            $notifyUser = false;
            foreach ($formdata as $pattern => $value){                                
                if (($pattern == 'notify')&&($value==true)){
                   $notifyUser = true;
                } else{
                    $placeholders[$pattern] = $value;
                }
            }
                $now = new \DateTime('now');
                $this->notificationManager->CreateEmailNotification($now, $placeholders['sponsor_email'], 'sponsor sign up email notification');
                $this->notificationManager->CreateSMSNotification($now, $placeholders['sponsor_phone'], 'sponsor sign up sms notification');
                $this->notificationManager->CreateEmailNotification($now, $placeholders['user_email'], 'user sign up email notification');
                
            if ($notifyUser){
                
                $beforeEventDateModifier = $this->settingsManager->getSettingValue('beforeEventDateModifier');
                $datetimeBeforeEvent = $event->getStartDateTime()->modify($beforeEventDateModifier);
                
                $this->notificationManager->CreateEmailNotification($datetimeBeforeEvent, $placeholders['user_email'], 'user before event email notification');
                $this->notificationManager->CreateSMSNotification($datetimeBeforeEvent, $placeholders['user_phone'], 'user before event sms notification');
            }
            
            $this->placeholderManager->assignPlaceholdersToSession($placeholders);                        
            $this->memberManager->updateMemberData($placeholders);
            
            return array('message'=>'You are successfully SignedUp');
        }        
        return array('form'=>$form->createView());
    }
}