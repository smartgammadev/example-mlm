<?php
namespace Success\TreasureBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;

class AccountManagerTest extends ServiceTest
{
    
    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\TreasureBundle\Service\AccountManager';
    
    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
    }
    
    public function testDoAccountOperation()
    {
        $member = $this->getSponsorMember();
        $this->instance->doAccountOperation($member, 1.02, 'referal');
        $this->instance->doAccountOperation($member, 2.03, 'referal');
        $this->instance->doAccountOperation($member, 4.04, 'referal');
        
        $result = $this->instance->getOverallAccountBalance($member);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\TreasureBundle\Entity\AccountBalance', $result);
        $this->assertEquals(7.09, $result->getAmount());
    }
    
    public function testGetSubAccountBalance()
    {
        $member = $this->getSponsorMember();
        $result = $this->instance->getSubAccountBalance($member, 'referal');
        $this->assertNotNull($result);
        $this->assertEquals('double', gettype($result));
    }
    
    private function getSponsorMember()
    {
        /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
        $memberManager = $this->container->get('success.member.member_manager');
        return $memberManager->resolveMemberByExternalId('test_accoun@domain.fake', '4success.bz@gmail.com');
    }
}
