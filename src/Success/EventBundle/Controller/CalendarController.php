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
     * @var \Success\EventBundle\Service\EventManager
     * @DI\Inject("success.event.event_manager")
     */
    private $eventManager;
    
    /**
     * @var \Success\SettingsBundle\Service\SettingsManager
     * @DI\Inject("success.settings.settings_manager")
     */    
    private $settingsManager;
    
    /**
     * @var \Success\PlaceholderBundle\Service\PlaceholderManager
     * @DI\Inject("success.placeholder.placeholder_manager")
     */    
    private $placeholderManager;
    
    /**
     * @var \Success\MemberBundle\Service\MemberManager
     * @DI\Inject("success.member.member_manager")
     */        
    private $memberManager;
    
    /**
     * @Route("/{template}/{slug}", name="show_calendar")
     * @Template()
     */
    public function showAction(Request $request)
    { 
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
        if (!$event){
            throw $this->createNotFoundException('No event found for id='.$eventId);
        }        
        
        $minutesToVisitEvent = $this->settingsManager->getSettingValue('minutesToVisitEvent');

        $now = new \DateTime('now');
        $allowVisitEvent = ($event->getStartDateTime()->getTimestamp() - $now->getTimestamp() < $minutesToVisitEvent*60);
        $isPastEvent = $event->getStartDateTime()->getTimestamp() < $now->getTimestamp();                        
        
        $externalLink = $this->eventManager->GenerateExternalLinkForWebinarEvent($event);
        return array('event' => $event,
                    'allowVisitEvent' => $allowVisitEvent,
                    'isPastEvent' => $isPastEvent,
                    'externalLink' => $externalLink,
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
        
        $form = $this->createForm(new SignupType($this->placeholderManager, $eventId));
        
        $form->handleRequest($request);

        
        if ($form->isValid()) {            
            $placeholders = $this->placeholderManager->getPlaceholdersFromSession();
            
            $formdata = $form->getData();
            
            $notifyUserBeforeEvent = false;
            foreach ($formdata as $pattern => $value){                                
                if (($pattern == 'notify')&&($value==true)){
                   $notifyUserBeforeEvent = true;
                } else{
                    $placeholders[$pattern] = $value;
                }
            }            
            $now = new \DateTime('now');
            $memberSignedUp = $this->memberManager->resolveMemberByExternalId($placeholders['user_email']);
            
            $this->placeholderManager->assignPlaceholdersToSession($placeholders);                        
            $this->memberManager->updateMemberData($placeholders);            
            
            if ($this->eventManager->SignUpMemberForEvent($memberSignedUp, $event, $now, $notifyUserBeforeEvent)){
                $message = 'You has already signed up to this event.';
            }else{
                $message = 'You have successfully signed up to this event.';
            }            
            return array('message' => $message);
        }        
        return array('form' => $form->createView());
    }

    /**
     * @Route("/nearest", name="show_nearest_events")
     * @Template()
     */
    
    public function nearestAction()
    {
        $placeholders = $request->query->all();                
        $this->placeholderManager->assignPlaceholdersToSession($placeholders);
        return array();
    }
}