<?php

namespace Success\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Success\EventBundle\Form\Type\SignupType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
     * @var \Success\MemberBundle\Service\UserManager
     * @DI\Inject("success.member.user_manager")
     */
    private $userManager;

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
    public function eventAction($eventId)
    {
        $event = $this->eventManager->getEventById($eventId);
        if (!$event) {
            throw $this->createNotFoundException('No event found for id=' . $eventId);
        }

        $minutesToVisitEvent = $this->settingsManager->getSettingValue('minutesToVisitEvent');

        $now = new \DateTime('now');
        $allowVisitEvent = ($event->getStartDateTime()->getTimestamp() - $now->getTimestamp() < $minutesToVisitEvent * 60);
        $isPastEvent = $event->getStartDateTime()->getTimestamp() < $now->getTimestamp();

        $externalLink = $this->eventManager->generateExternalLinkForWebinarEvent($event);
        return array('event' => $event,
            'allowVisitEvent' => $allowVisitEvent,
            'isPastEvent' => $isPastEvent,
            'externalLink' => $externalLink,
        );
    }

    /**
     * @Route("/day", name="day_events")
     * @Template()
     */
    public function dayAction()
    {
        $startDate = new \DateTime();
        $endDate = clone $startDate;
        $endDate->setTime(23, 59, 59);

        $events = array('date' => $startDate, 'events' => $this->eventManager->getEventsByDateRange($startDate, $endDate));
        return array('eventsToday' => $events);
    }

    /**
     * @Route("/week", name="week_events")
     * @Template()
     */
    public function weekAction()
    {
        $startDate = new \DateTime();
        $endDate = clone $startDate;

        $startDate->modify('+1 days');
        $startDate->setTime(0, 0, 1);

        $endDate->modify('+7 days');
        $endDate->setTime(23, 59, 59);

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($startDate, $interval, $endDate);

        $eventsOfWeek = [];
        $counter = 0;

        foreach ($daterange as $date) {
            $dayBegin = $date;
            $dayEnd = clone $date;
            $dayEnd->setTime(23, 59, 59);
            $dayEvents = $this->eventManager->getEventsByDateRange($dayBegin, $dayEnd);
            $eventsOfWeek[] = array('date' => $date, 'events' => $dayEvents);
            $counter += count($dayEvents);
        }
        return array('weekEventsCount' => $counter, 'eventsOfWeek' => $eventsOfWeek);
    }

    /**
     * @Route("/next", name="next_event")
     * @Template()
     */
    public function nextAction()
    {
        $minutesBeforeToVisitEvent = $this->settingsManager->getSettingValue('minutesBeforeToVisitEvent');
        $minutesAfterToVisitEvent = $this->settingsManager->getSettingValue('minutesAfterToVisitEvent');

        $nowDate = new \DateTime('now');
        $nowDate->modify("-$minutesAfterToVisitEvent minutes");

        $lastDayOfWeek = clone $nowDate;
        $lastDayOfWeek->modify('+7 days');
        $dayEvents = $this->eventManager->getEventsByDateRange($nowDate, $lastDayOfWeek);

        if (count($dayEvents) !== 0) {
            $nextEvent = $dayEvents[0];
        } else {
            $nextEvent = null;
        }
        $current = new \DateTime();
        $userAccess = false;
        $allowToVisit = false;
        $externalLink = '';
        if ($nextEvent) {
            $userAccess = $this->eventManager->getEventAccessForUser($nextEvent);
            $externalLink = $this->eventManager->generateExternalLinkForWebinarEvent($nextEvent);
            $allowToVisit = ($nextEvent->getStartDateTime()->getTimestamp() - $current->getTimestamp() < $minutesBeforeToVisitEvent * 60);
        }
        return array('currentDateTime' => $current,
            'allowToVisit' => $allowToVisit,
            'userAccess' => $userAccess,
            'externalLink' => $externalLink,
            'nextEvent' => $nextEvent);
    }

    /**
     * @Route("/nearest", name="show_nearest_events")
     * @Template()
     */
    public function nearestAction(Request $request)
    {
        $placeholders = $request->query->all();
        $this->placeholderManager->assignPlaceholdersToSession($placeholders);

        return array();
    }

    /**
     * @Route("/{template}/event/{eventId}/signup", name="calendar_event_signup", requirements={"eventId"="\d+"})
     * @Template()
     */
    public function signupAction($eventId, Request $request)
    {
        /**
         * @var \Success\EventBundle\Entity\BaseEvent Event for sign up
         */
        $event = $this->eventManager->getEventById($eventId);

        if (!$this->eventManager->getEventAccessForUser($event)) {
            $message = 'Извините. Доступ к даному вебинару разрешен только для партнеров с VIP статусом.';
            return array('message' => $message);
        }

        $form = $this->createForm(new SignupType($this->placeholderManager, $eventId, $this->get('router')));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $placeholders = $this->placeholderManager->getPlaceholdersFromSession();
            $formdata = $form->getData();
            $notifyUserBeforeEvent = false;
            foreach ($formdata as $pattern => $value) {
                if (($pattern == 'notify') && ($value == true)) {
                    $notifyUserBeforeEvent = true;
                } else {
                    $placeholders[$pattern] = $value;
                }
            }
            $now = new \DateTime('now');
            $memberSignedUp = $this->memberManager->resolveMemberByExternalId($placeholders['user_email']);
            $userSignedUp = $this->userManager->resolveUserByExternalId($placeholders['user_email']);
            $this->userManager->loginUser($userSignedUp);
            $this->placeholderManager->assignPlaceholdersToSession($placeholders);
            $this->memberManager->updateMemberData($placeholders);
            if ($this->eventManager->signUpMemberForEvent($memberSignedUp, $event, $now, $notifyUserBeforeEvent)) {
                $message = 'Вы уже зарегистрированы на этот вебинар.';
            } else {
                $message = 'Поздравляем, Вы успешно зарегистрированы на вебинар!';
            }
            return array('message' => $message);
        }
        return array('form' => $form->createView());
    }
}
