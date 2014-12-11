<?php
namespace Success\EventBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlaceholderManagerTest
 *
 * @author develop1
 */
class PlaceholderManagerTest extends ServiceTest {
    
    /**
     * Target class name for the instance creation
     * 
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\PlaceholderBundle\Service\PlaceholderManager';
    
    /**
     * Selector to pass container to constructor of class
     * 
     * @var bool
     */
    protected $isConstructContainer = false;
    
    /**
     * List of mocking repositories when $isMockEmulation = true;
     * 
     * @var array
     */  
    protected $emulatedRepositoriesList = array(
       // 'Success\EventBundle\Mocks\Repository\EventRepositoryMockContainer',
    );
  
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();    
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
        $this->instance->setRequest($this->container->get('request'));
    }
    
    /**
     * @covers \Success\PlaceholderBundle\Service\PlaceholderManager::resolveExternalPlaceholder($fullName)
     */
    public function testResolveExternalPlaceholder()
    {
        $result = $this->instance->resolveExternalPlaceholder('user_first_name');
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PlaceholderBundle\Entity\ExternalPlaceholder', $result);
    }
    
    /**
     * @covers \Success\PlaceholderBundle\Service\PlaceholderManager::assignPlaceholdersToSession($placeholders)
     */
    public function testAssignPlaceholdersToSession()
    {
        //$placeholders = ['user_first_name' => 'Usrername',
        //    'user_last_name' => 'Usrername',];
        //$this->instance->assignPlaceholdersToSession($placeholders);
    }
    
    
    /**
     * @covers \Success\PlaceholderBundle\Service\PlaceholderManager::getPlaceholdersValuesByTypePattern($typePattern)
     */
    public function testGetPlaceholdersValuesByTypePattern()
    {
        //$result = $this->instance->getPlaceholdersValuesByTypePattern('user');
    }
    
    /**
     * @covers \Success\PlaceholderBundle\Service\PlaceholderManager::getPlaceholderTypes()
     */
    public function testGetPlaceholderTypes()
    {
        $result = $this->instance->getPlaceholderTypes();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PlaceholderBundle\Entity\PlaceholderType', $result[0]);
    }        
}