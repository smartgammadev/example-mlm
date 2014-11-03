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
        return array('event' => $event, 'allowVisitEvent' => $allowVisitEvent, 'isPastEvent' => $isPastEvent );
    }
    
    /**
     * @Route("/{template}/event/{eventId}/signup", name="calendar_event_signup", requirements={"eventId"="\d+"})
     * @Template()
     */
    public function signupAction($eventId, Request $request) {
        $form = $this->createForm(new SignupType($this->placeholderManager));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $placeholders = $this->placeholderManager->getPlaceholdersFromSession();            
            $formdata = $form->getData();
            
            foreach ($formdata as $pattern=>$value ){
                $placeholders[$pattern]=$value;
            }
            $this->placeholderManager->assignPlaceholdersToSession($placeholders);
            
            $memberIdentityPlaceholder = $this->settingsManager->getSettingValue('memberIdentityPlaceholder');
            $this->memberManager->UpdateMemberData($placeholders,$memberIdentityPlaceholder);
            
            return array('message'=>'You are successfully SignedUp');
        }
        
        return array('form'=>$form->createView());
    }
}