<?php

namespace Success\Behat;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Behat\Gherkin\Node\TableNode;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{

    const SONATA_UNIQID = 'behat';
    const ROLE_SPONSOR = 'ROLE_4SUCCESS_SPONSOR';
    const ROLE_USER = 'ROLE_4SUCCESS_USER';

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    private $em;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    private $securityContext;

    public function __construct(Kernel $kernel)
    {
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->securityContext = $kernel->getContainer()->get('security.context');
    }

    /**
     * @Then I wait for AJAX to finish
     */
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(3000);
    }

    /**
     * @When I click :arg1
     */
    public function iClick($selector)
    {
        try {
            $element = $this->getSession()->getPage()->find('css', "#" . $selector);
        } catch (\Exception $e) {
            $element = $this->getSession()->getPage()->find('css', $selector);
        }
        if (null === $element) {
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $selector);
        }
        $this->getSession()->getDriver()->click($element->getXPath());
    }

    /**
     * @Given I am logged in as admin
     */
    public function iAmLoggedInAsAdmin()
    {
        $this->visit('/admin/login');
        $this->fillField('username', 'admin');
        $this->fillField('password', 'admin');
        $this->pressButton('_submit');
    }

    /**
     * @Given there is no events in DB
     */
    public function thereIsNoEventsInDb()
    {
        $signUps = $this->em->getRepository('Success\EventBundle\Entity\EventSignUp')->findAll();
        $events = $this->em->getRepository('Success\EventBundle\Entity\BaseEvent')->findAll();
        $eventRepeats = $this->em->getRepository('Success\EventBundle\Entity\EventRepeat')->findAll();

        foreach ($eventRepeats as $eventRepeat) {
            $this->em->remove($eventRepeat);
        }

        foreach ($signUps as $signUp) {
            $this->em->remove($signUp);
        }

        foreach ($events as $event) {
            $this->em->remove($event);
        }
        $this->em->flush();
    }

    /**
     * @Given there is no notifications in DB
     */
    public function thereIsNoNotificationsInDb()
    {
        
    }

    /**
     * @Then I want to create new audience
     */
    public function iWantToCreateNewAudience()
    {
        $this->visit('admin/success/salesgenerator/audience/create?uniqid=' . self::SONATA_UNIQID);
    }

    /**
     * @Then I want to create new event
     */
    public function iWantToCreateNewEvent()
    {
        $this->visit('admin/success/event/webinarevent/create?uniqid=' . self::SONATA_UNIQID);
    }

    /**
     * @Then I fill :arg1 with current date plus :arg2 minutes
     */
    public function iFillWithCurrentDatePlusMinutes($filedName, $minutes)
    {
        $currentDate = new \DateTime();
        $currentDate->modify("+{$minutes} minutes");
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $currentDate->format('Y-m-d H:i:s') . ' +0300');
    }

    /**
     * @Then I fill :arg1 with :arg2
     */
    public function iFillWith($filedName, $value)
    {
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $value);
    }

    /**
     * @Then I select :arg1 in :arg2
     */
    public function iSelectIn($value, $filedName)
    {
        $this->selectOption(self::SONATA_UNIQID . '_' . $filedName, $value);
    }

    /**
     * @When I go to :arg1 with :arg2 placeholders
     */
    public function iGoToWithPlaceholders($url, $placeholders)
    {
        $this->visit($url . '?' . $placeholders);
    }

    /**
     * @Then I reset sonata unique ID
     */
    public function iResetSonataUniqueId()
    {
        $this->visit($this->getSession()->getCurrentUrl() . '?uniqid=' . self::SONATA_UNIQID);
    }

    /**
     * @Then I fill :arg1 with current date plus :arg2 days
     */
    public function iFillWithCurrentDatePlusDays($filedName, $days)
    {
        $currentDate = new \DateTime();
        $currentDate->modify("+{$days} days");
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $currentDate->format('Y-m-d H:i:s') . ' +0300');
    }

    /**
     * @Then I check :arg1 iCheckbox
     */
    public function iCheckIcheckbox($iCheckId)
    {
        $selector = '#' . self::SONATA_UNIQID . '_' . $iCheckId;
        $javascript = "$('{$selector}').iCheck('check');";
        $this->getSession()->getDriver()->evaluateScript($javascript);
    }

    /**
     * @Then I should have member with id :arg1
     */
    public function iShouldHaveMemberWithId($memberId)
    {
        $membersCount = $this->em->createQuery('select count(m) from SuccessMemberBundle:Member m where m.externalId=:external_id')
                ->setParameter('external_id', $memberId)
                ->getSingleScalarResult();
        if ($membersCount == 0) {
            $message = 'Member with id "' . $memberId . '", not found in DB. But should exist.';
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then I should have :arg2 email notification to :arg1
     */
    public function iShouldHaveEmailNotificationTo($count, $destination)
    {
        $notificationsCount = $this->em
                ->createQuery('select count(n) from SuccessNotificationBundle:EmailNotification n where n.destination=:destination')
                ->setParameter('destination', $destination)
                ->getSingleScalarResult();

        if ($notificationsCount !== $count) {
            $message = sprintf('Found %s email notifications to "%s". But should be %s', $notificationsCount, $destination, $count);
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then I should have :arg2 SMS notification to :arg1
     */
    public function iShouldHaveSmsNotificationTo($count, $destination)
    {
        $notificationsCount = $this->em
                ->createQuery('select count(n) from SuccessNotificationBundle:SMSNotification n where n.destination=:destination')
                ->setParameter('destination', $destination)
                ->getSingleScalarResult();

        if ($notificationsCount !== $count) {
            $message = sprintf('Found %s SMS notifications to "%s". But should be %s', $notificationsCount, $destination, $count);
            throw new \Behat\Mink\Exception\ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then I should see :arg1 button enabled
     */
    public function iShouldSeeButtonEnabled($buttonName)
    {
        $button = $this->getSession()->getPage()->findLink($buttonName);
        if ($button == null) {
            $message = 'Link with id|tittle|text|img-alt ' . $buttonName . ', not found on page.';
            throw new ExpectationException($message, $this->getSession());
        }

        if ($button->hasClass('disabled')) {
            $message = 'Link "' . $buttonName . '" is disabled. But should be enabled.';
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then I should see :arg1 button disabled
     */
    public function iShouldSeeButtonDisabled($buttonName)
    {
        $button = $this->getSession()->getPage()->findLink($buttonName);
        if ($button == null) {
            $message = 'Link with id|tittle|text|img-alt ' . $buttonName . ', not found on page.';
            throw new ExpectationException($message, $this->getSession());
        }

        if (!$button->hasClass('disabled')) {
            $message = 'Link "' . $buttonName . '" is enabled. But should be disabled.';
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Given the following events exist:
     */
    public function theFollowingEventsExist(TableNode $table)
    {
        $eventRepo = $this->em->getRepository('SuccessEventBundle:WebinarEvent');
        $webinarEvents = $eventRepo->findAll();
        foreach ($webinarEvents as $event) {
            $this->em->remove($event);
        }
        $accessTypeRepo = $this->em->getRepository('SuccessEventBundle:EventAccessType');
        $eventTypeRepo = $this->em->getRepository('SuccessEventBundle:EventType');

        $hash = $table->getHash();
        foreach ($hash as $row) {
            $date = new \DateTime();
            $date->modify($row['date_modifier']);
            $event = new \Success\EventBundle\Entity\WebinarEvent();
            $event->setName($row['name']);
            $event->setStartDateTime($date);
            $event->setDescription($row['description']);
            $event->setPattern('pattern');

            $accessType = $accessTypeRepo->findOneBy(['name' => $row['access_type']]);
            $eventType = $eventTypeRepo->findOneBy(['name' => $row['type']]);

            $event->setAccessType($accessType);
            $event->setEventType($eventType);
            $event->setUrl('http://www.url.com');
            $this->em->persist($event);
        }
        $this->em->flush();
    }

    /**
     * @Given I am not logged
     */
    public function iAmNotLogged()
    {
        $this->securityContext->setToken(new AnonymousToken('user', 'anon.', []));
    }

    /**
     * @Then member :arg1 should be sponsor
     */
    public function memberShouldBeSponsor($memberExternalId)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $member = $memberRepo->findOneBy(['externalId' => $memberExternalId]);
        $roles = $member->getRoles();
        if ($roles[0] != self::ROLE_SPONSOR) {
            $message = sprintf(
                    'Role of "%s" member should be "%s", but it is "%s"', $memberExternalId, self::ROLE_SPONSOR, $roles[0]
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then member :arg1 should be user
     */
    public function memberShouldBeUser($memberExternalId)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $member = $memberRepo->findOneBy(['externalId' => $memberExternalId]);
        $roles = $member->getRoles();
        if ($roles[0] != self::ROLE_USER) {
            $message = sprintf(
                'Role of "%s" member should be "%s", but it is "%s"',
                $memberExternalId,
                self::ROLE_USER,
                $roles[0]
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then sponsor of :arg1 member should be :arg2
     */
    public function sponsorOfMemberShouldBe($userExternalId, $sponsorExternalId)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $userMember = $memberRepo->findOneBy(['externalId' => $userExternalId]);
        if ($userMember->getSponsor()->getExternalId() != $sponsorExternalId) {
            $message = sprintf(
                    'Sponsor of "%s" member is %s, but should be %s.', $userExternalId, $userMember->getSponsor()->getExternalId(), $sponsorExternalId
            );
            throw new ExpectationException($message);
        }
    }

    /**
     * @Then sponsor :arg1 should have :arg2 referals
     */
    public function sponsorShouldHaveReferals($sponsorExternalId, $referalsCount)
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $sponsor = $memberRepo->findOneBy(['externalId' => $sponsorExternalId]);
        $childCount = $memberRepo->childCount($sponsor);

        if ($childCount != (integer) $referalsCount) {
            $message = sprintf(
                    'Member "%s" should have %s referals, but it has %s', $sponsorExternalId, $referalsCount, $childCount
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Given there is no new members in DB
     */
    public function thereIsNoNewMembersInDb()
    {
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $members = $memberRepo->findAll();

        foreach ($members as $member) {
            if ($member->getExternalId() != '4success.bz@gmail.com') {
                $this->em->remove($member);
            }
        }
        $this->em->flush();
    }
}
