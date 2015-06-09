<?php

namespace Success\Behat;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\QueryString;

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{

    const SONATA_UNIQID = 'behat';
    const ROLE_SPONSOR = 'ROLE_4SUCCESS_SPONSOR';
    const ROLE_USER = 'ROLE_4SUCCESS_USER';
    const PLACEHOLDER_USER_TYPE_NAME = 'user';
    /* @var $kernel \Symfony\Component\HttpKernel\Kernel */

    private $kernel;

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Get repository by name.
     * @param string $resource
     * @return RepositoryInterface
     */
    public function getRepository($resource)
    {
        return $this->getEntityManager()->getRepository($resource);
    }

    /**
     * Get entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getService('doctrine')->getManager();
    }

    public function getSecurityContext()
    {
        return $this->getService('security.context');
    }

    /**
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * Get service by id.
     * @param string $id
     * @return object
     */
    public function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * @Then I wait for AJAX to finish
     */
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(3000);
    }

    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($selector)
    {
        try {
            $element = $this->getSession()->getPage()->find('css', "#" . $selector);
        } catch (\Exception $e) {
            $element = $this->getSession()->getPage()->find('css', $selector);
        }
        if (null === $element) {
            throw new \Behat\Mink\Exception\ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $selector);
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
        $signUps = $this->getRepository('Success\EventBundle\Entity\EventSignUp')->findAll();
        $events = $this->getRepository('Success\EventBundle\Entity\BaseEvent')->findAll();
        $eventRepeats = $this->getRepository('Success\EventBundle\Entity\EventRepeat')->findAll();

        foreach ($eventRepeats as $eventRepeat) {
            $this->getEntityManager()->remove($eventRepeat);
        }

        foreach ($signUps as $signUp) {
            $this->getEntityManager()->remove($signUp);
        }

        foreach ($events as $event) {
            $this->getEntityManager()->remove($event);
        }
        $this->getEntityManager()->flush();
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
     * @Given /^I fill "([^"]*)" with current date plus "([^"]*)" minutes$/
     */
    public function iFillWithCurrentDatePlusMinutes($filedName, $minutes)
    {
        $currentDate = new \DateTime();
        $currentDate->modify("+{$minutes} minutes");
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $currentDate->format('Y-m-d H:i:s') . ' +0300');
    }

    /**
     * @Given /^I fill "([^"]*)" with "([^"]*)"$/
     */
    public function iFillWith($filedName, $value)
    {
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $value);
    }

    /**
     * @Then /^I select "([^"]*)" in "([^"]*)"$/
     */
    public function iSelectIn($value, $filedName)
    {
        $this->selectOption(self::SONATA_UNIQID . '_' . $filedName, $value);
    }

    /**
     * @When /^I go to "([^"]*)" with "([^"]*)" placeholders$/
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
     * @Then /^I fill "([^"]*)" with current date plus "([^"]*)" days$/
     */
    public function iFillWithCurrentDatePlusDays($filedName, $days)
    {
        $currentDate = new \DateTime();
        $currentDate->modify("+{$days} days");
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $currentDate->format('Y-m-d H:i:s') . ' +0300');
    }

    /**
     * @Given /^I check "([^"]*)" iCheckbox$/
     */
    public function iCheckIcheckbox($iCheckId)
    {
        $selector = '#' . self::SONATA_UNIQID . '_' . $iCheckId;
        $javascript = "$('{$selector}').iCheck('check');";
        $this->getSession()->getDriver()->evaluateScript($javascript);
    }

    /**
     * @Then /^I should have member with id "([^"]*)"$/
     */
    public function iShouldHaveMemberWithId($memberId)
    {
        $membersCount = $this->getEntityManager()->createQuery('select count(m) from SuccessMemberBundle:Member m where m.externalId=:external_id')
                ->setParameter('external_id', $memberId)
                ->getSingleScalarResult();
        if ($membersCount == 0) {
            $message = 'Member with id "' . $memberId . '", not found in DB. But should exist.';
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then /^I should have (\d+) email notification to "([^"]*)"$/
     */
    public function iShouldHaveEmailNotificationTo($count, $destination)
    {
        $notificationsCount = $this->getEntityManager()
                ->createQuery('select count(n) from SuccessNotificationBundle:EmailNotification n where n.destination=:destination')
                ->setParameter('destination', $destination)
                ->getSingleScalarResult();

        if ($notificationsCount !== $count) {
            $message = sprintf('Found %s email notifications to "%s". But should be %s', $notificationsCount, $destination, $count);
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then /^I should have (\d+) SMS notification to "([^"]*)"$/
     */
    public function iShouldHaveSmsNotificationTo($count, $destination)
    {
        $notificationsCount = $this->getEntityManager()
                ->createQuery('select count(n) from SuccessNotificationBundle:SMSNotification n where n.destination=:destination')
                ->setParameter('destination', $destination)
                ->getSingleScalarResult();

        if ($notificationsCount !== $count) {
            $message = sprintf('Found %s SMS notifications to "%s". But should be %s', $notificationsCount, $destination, $count);
            throw new \Behat\Mink\Exception\ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Then /^I should see "([^"]*)" button enabled$/
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
     * @Given /^I should see "([^"]*)" button disabled$/
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
        $eventRepo = $this->getRepository('SuccessEventBundle:WebinarEvent');
        $webinarEvents = $eventRepo->findAll();
        foreach ($webinarEvents as $event) {
            $this->getEntityManager()->remove($event);
        }
        $accessTypeRepo = $this->getRepository('SuccessEventBundle:EventAccessType');
        $eventTypeRepo = $this->getRepository('SuccessEventBundle:EventType');

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
            $this->getEntityManager()->persist($event);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @Given I am not logged
     */
    public function iAmNotLogged()
    {
        $this->getSecurityContext()->setToken(new AnonymousToken('user', 'anon.', []));
    }

    /**
     * @Given /^member "([^"]*)" should be sponsor$/
     */
    public function memberShouldBeSponsor($memberExternalId)
    {
        $memberRepo = $this->getRepository('SuccessMemberBundle:Member');
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
     * @Given /^member "([^"]*)" should be user$/
     */
    public function memberShouldBeUser($memberExternalId)
    {
        $memberRepo = $this->getRepository('SuccessMemberBundle:Member');
        $member = $memberRepo->findOneBy(['externalId' => $memberExternalId]);
        $roles = $member->getRoles();
        if ($roles[0] != self::ROLE_USER) {
            $message = sprintf(
                    'Role of "%s" member should be "%s", but it is "%s"', $memberExternalId, self::ROLE_USER, $roles[0]
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Given /^sponsor of "([^"]*)" member should be "([^"]*)"$/
     */
    public function sponsorOfMemberShouldBe($userExternalId, $sponsorExternalId)
    {
        $memberRepo = $this->getRepository('SuccessMemberBundle:Member');
        $userMember = $memberRepo->findOneBy(['externalId' => $userExternalId]);
        if ($userMember->getSponsor()->getExternalId() != $sponsorExternalId) {
            $message = sprintf(
                    'Sponsor of "%s" member is %s, but should be %s.', $userExternalId, $userMember->getSponsor()->getExternalId(), $sponsorExternalId
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }

    /**
     * @Given /^sponsor "([^"]*)" should have (\d+) referals$/
     */
    public function sponsorShouldHaveReferals($sponsorExternalId, $referalsCount)
    {
        $memberRepo = $this->getRepository('SuccessMemberBundle:Member');
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
        $memberRepo = $this->getRepository('SuccessMemberBundle:Member');
        $members = $memberRepo->findAll();

        foreach ($members as $member) {
            if ($member->getExternalId() != '4success.bz@gmail.com') {
                $this->getEntityManager()->remove($member);
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @When /^I go to "([^"]*)" with placeholders$/
     */
    public function iGoToWithPlaceholders2($url, TableNode $placeholders)
    {
        $queryString = new QueryString($placeholders->getRowsHash());
        $this->visit($url . '?' . $queryString->__toString());
    }

    /**
     * @Then /^"([^"]*)" of member "([^"]*)" should be "([^"]*)"$/
     */
    public function ofMemberShouldBe($placeholderPattern, $externalId, $data)
    {
        /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
        $memberManager = $this->getService('success.member.member_manager');
        $member = $memberManager->getMemberByExternalId($externalId);

        /* @var $placeholderManager \Success\PlaceholderBundle\Service\PlaceholderManager */
        $placeholderManager = $this->getService('success.placeholder.placeholder_manager');
        $placeholder = $placeholderManager->resolveExternalPlaceholder(self::PLACEHOLDER_USER_TYPE_NAME . '_' . $placeholderPattern);

        $memberData = $memberManager->getMemberData($member, $placeholder);

        if ($data != $memberData) {
            $message = sprintf(
                    'Value of "%s", of member "%s" equals to "%s". But should be "%s".', $placeholderPattern, $externalId, $memberData, $data
            );
            throw new ExpectationException($message, $this->getSession());
        }
    }
    /**
     * @Then /^I want to create new static page$/
     */
    public function iWantToCreateNewStaticPage()
    {
        $this->visit('admin/success/staticpage/page/create?uniqid=' . self::SONATA_UNIQID);

    }

    /**
     * @Then /^I fill in ckeeditor with "([^"]*)"$/
     */
    public function iFillInCkeeditorWith($arg1)
    {
        $jsQuery = "CKEDITOR.instances[Object.keys(CKEDITOR.instances)[0]].insertHtml('$arg1')";
        $this->getSession()->getDriver()->evaluateScript($jsQuery);
    }

    /**
     * @Then /^I click add paket and select "([^"]*)"$/
     */
    public function iClickAddPaketAndSelect($arg1)
    {
        $link = $this->getSession()->getCurrentUrl();
        $this->visit('/admin/success/staticpage/pageproductpricing/create?uniqid='.self::SONATA_UNIQID.'&code=sonata.admin.page_pricing&pcode=sonata.admin.page_admin&puniqid='.self::SONATA_UNIQID);
        $elem = $this->getSession()->getPage();
        $select = $elem->findById(self::SONATA_UNIQID.'_productPricing')->selectOption($arg1);
        $button = $elem->findButton('btn_create_and_edit')->click();
        $this->visit($link);
    }

}
