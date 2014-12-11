<?php

namespace Success\EventBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;
//use LaMelle\ImageBundle\Mocks\Repository\ImageRepositoryMock;

class SettingsManagerTest extends ServiceTest
{
    const MINUTES_AFTER = 'minutesAfterToVisitEvent';
    /**
     * Target class name for the instance creation
     * 
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\SettingsBundle\Service\SettingsManager';
    
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
    }   
    
    /**
     * @covers \Success\EventBundle\Service\SettingsManager::getSettingValue
     */
    public function testGetSettingValue()
    {
        $result = $this->instance->getSettingValue(self::MINUTES_AFTER);        
        $this->assertNotNull($result);
        
        $this->setExpectedException('Exception');
        $this->instance->getSettingValue('nonExistanceSetting');
    }    
}
