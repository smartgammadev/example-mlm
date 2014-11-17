<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    const SONATA_UNIQID='s5444ed96d21e7';


    /**
     * @Given /^I am logged in as admin$/
     */
    public function iAmLoggedInAsAdmin()
    {
        $this->visit('/admin/login');
        $this->fillField('username', 'admin');
        $this->fillField('password', 'admin');
        $this->pressButton('_submit');
    }

    /**
     * @Given /^I wait for AJAX to finish$/
     */    
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(3000);
    }
    
    /**
     * @Given /^I fill in "([^"]*)" with current date$/
     */
    public function iFillInWithCurrentDate($arg1)
    {
        //$session = $this->getSession();
        $currentDate = date('c',  time());
        $this->fillField($arg1, $currentDate);
        //throw new PendingException();
    }
    
    /**
     * @When /^I click "([^"]*)"$/
     */
    public function iClick($selector)
    {
        /* first by id then by css selector */
        try {
            $element = $this->getSession()->getPage()->find('css', "#" . $selector);
        } catch (\Exception $e) {
            $element = $this->getSession()->getPage()->find('css', $selector);
        }

        if (null === $element){
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $selector);
        }
        
        $this->getSession()->getDriver()->click($element->getXPath());
    }
    
    /**
     * @Then /^I want to create new event$/
     */
    public function iWantToCreateNewEvent()
    {
        $this->visit('admin/success/event/webinarevent/create?uniqid='.self::SONATA_UNIQID);
        //throw new PendingException();
    }
    
    /**
     * @Then /^I fill "([^"]*)" with current date plus "([^"]*)" minutes$/
     */
    public function iFillWithCurrentDatePlusMinutes($filedName, $minutes)
    {
        $currentDate = date('c',  time()+(60*$minutes));
        $this->fillField(self::SONATA_UNIQID.'_'.$filedName, $currentDate);
    }
    
    /**
     * @Then /^I fill "([^"]*)" with "([^"]*)"$/
     */
    public function iFillWith($filedName, $value)
    {
        $this->fillField(self::SONATA_UNIQID.'_'.$filedName, $value);
    }
   
    /**
     * @Given /^I select "([^"]*)" in "([^"]*)"$/
     */
    public function iSelectIn($value, $filedName)
    {
        $this->selectOption(self::SONATA_UNIQID.'_'.$filedName, $value);
    }
    
    
    /**
     * @Then /^I go to "([^"]*)" with "([^"]*)" placeholders$/
     */
    public function iGoToWithPlaceholders($url, $placeholders)
    {
        $this->visit($url.'?'.$placeholders);
    }
    
}
