<?php

namespace Success\MemberBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;

class MemberManagerTest extends ServiceTest
{

    const MEMBER_IDENTIFY_PLACEHODER = 'email';

    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\MemberBundle\Service\MemberManager';

    /**
     * Selector to pass container to constructor of class
     * @var bool
     */
    protected $isConstructContainer = false;

    /**
     * List of mocking repositories when $isMockEmulation = true;
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
        $result = $this->instance->resolveMemberByExternalId('fake_mail@fake_domain.fake', '4success.bz@gmail.com');
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\MemberBundle\Entity\Member', $result);
        
        $resultNew = $this->instance->getMemberByExternalId('fake_mail@fake_domain.fake');
        $this->assertNotNull($resultNew);
        $this->assertInstanceOf('Success\MemberBundle\Entity\Member', $resultNew);
        
        $sponsorExternalId = $resultNew->getSponsor()->getExternalId();
        $this->assertEquals($sponsorExternalId, '4success.bz@gmail.com');
    }
    
    /**
     * @covers \Success\MemberBundle\Service\MemberManager::getMemberByExternalId($externalId)
     */
    public function testGetMemberByExternalId()
    {
        $result = $this->instance->getMemberByExternalId('4success.bz@gmail.com');
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\MemberBundle\Entity\Member', $result);
    }
    
    /**
     * @covers \Success\MemberBundle\Service\MemberManager::getMemberByExternalId($externalId)
     */
    public function testNotFoundGetMemberByExternalId()
    {
        try {
            $this->instance->getMemberByExternalId('any-fake-member@anywhere-fake.fake');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $ex) {
            $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', $ex);
        }
    }
    
    /**
     * @covers \Success\MemberBundle\Service\MemberManager::getMemberReferalCount($sponsor)
     */
    public function testGetMemberReferalCount()
    {
        $sponsor = $this->instance->getMemberByExternalId('4success.bz@gmail.com');
        $referealsCount = $this->instance->getMemberReferalCount($sponsor);
        $this->assertInternalType("int", $referealsCount);
        $this->assertTrue($referealsCount >= 0);
    }
}
