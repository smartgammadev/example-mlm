<?php
namespace Success\Behat;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementHtmlException,
    Behat\Mink\Exception\ExpectationException,
    Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Mink,
    Behat\Mink\Session,
    Behat\Mink\Driver\Selenium2Driver;
use Gamma\Framework\Behat\PagesContext,
    Gamma\Framework\Behat\ApiContext;
use Behat\Behat\Context\Step;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    const SONATA_UNIQID='s5444ed96d21e7';
    
    protected $kernel;
    private $parameters;

    
    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }    
    
    
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
     *
     * @param string $resource
     *
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
     *
     * @param string $id
     *
     * @return object
     */
    public function getService($id)
    {
        return $this->getContainer()->get($id);
    }    
    
    
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

    /**
     * @Then /^I should have (\d+) notifications$/
     */
    public function iShouldHaveNotifications($arg1)
    {
        $notificationsCount = $this->getEntityManager()->createQuery('select count(n) from SuccessNotificationBundle:Notification n')->getSingleScalarResult();
        if ($notificationsCount !== $arg1) {
            $message = 'Count of notifications in DB not equals '.$arg1.', but equals '.$notificationsCount;
            throw new ExpectationException($message, $this->getSession());
        }        
    }
    
    /**
     * @Given /^I should have (\d+) members$/
     */
    public function iShouldHaveMembers($arg1)
    {
        $membersCount = $this->getEntityManager()->createQuery('select count(m) from SuccessMemberBundle:Member m')->getSingleScalarResult();
        if ($membersCount !== $arg1) {
            $message = 'Count of members in DB not equals '.$arg1.', but equals '.$membersCount;
            throw new ExpectationException($message, $this->getSession());
        }        
    }

    /**
     * @Then /^I should have member with "([^"]*)" id$/
     */
    public function iShouldHaveMemberWithId($memberId)
    {
        $membersCount = $this->getEntityManager()->createQuery('select count(m) from SuccessMemberBundle:Member m where m.externalId=:external_id')
                ->setParameter('external_id', $memberId)
                ->getSingleScalarResult();
        if ($membersCount == 0) {
            $message = 'Count of members with id '.$memberId.', is '.$membersCount;
            throw new ExpectationException($message, $this->getSession());
        }
        
    }
    
    
}