<?php

namespace Success\Behat;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements SnippetAcceptingContext
{
    const SONATA_UNIQID = 'behat';
    const ROLE_SPONSOR = 'ROLE_4SUCCESS_SPONSOR';
    const ROLE_USER = 'ROLE_4SUCCESS_USER';    
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager $em
     */
    private $em;
    
    public function __construct(Kernel $kernel)
    {
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');;
    }
    
    /**
     * @Then I wait for AJAX to finish
     */
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(1000);
        
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
     * @Given I have no events
     */
    public function iHaveNoEvents()
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
        $this->fillField(self::SONATA_UNIQID . '_' . $filedName, $currentDate->format('Y-m-d H:i:s').' +0300');
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
}
