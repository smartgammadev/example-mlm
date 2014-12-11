<?php
namespace Success\EventBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;
//use LaMelle\ImageBundle\Mocks\Repository\ImageRepositoryMock;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MemberManagerTest
 *
 * @author develop1
 */
class MemberManagerTest extends ServiceTest {
    
    const MEMBER_IDENTIFY_PLACEHODER = 'email';
    
    /**
     * Target class name for the instance creation
     * 
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\MemberBundle\Service\MemberManager';
    
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
        $this->instance->setPlaceholderManager($this->container->get('success.placeholder.placeholder_manager'));
    }
        
    /**
     * @covers \Success\MemberBundle\Service\MemberManager::resolveMemberByExternalId($externalId)
     */
    public function testResolveMemberByExternalId()
    {
        $result = $this->instance->resolveMemberByExternalId('fake_mail@fake_domain.fake');       
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\MemberBundle\Entity\Member', $result);
    }
    
}
