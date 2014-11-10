<?php
namespace Sucess\Behat;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
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
     * @Given /^I fill in "([^"]*)" with current date plus "([^"]*)" minutes$/
     */
    public function iFillInWithCurrentDatePlusMinutes($arg1, $minutes)
    {
        $currentDate = date('c',  time()+(60*$minutes));
        $this->fillField($arg1, $currentDate);
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
     * @param string $selector
     *
     * @throws ElementNotFoundException
     * @return void
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

        if (null === $element)
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value', $selector);

        $this->getSession()->getDriver()->click($element->getXPath());
    }
}
