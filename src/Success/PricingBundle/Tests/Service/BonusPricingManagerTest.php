<?php

namespace Success\PricingBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;

class BonusPricingManagerTest extends ServiceTest
{

    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\PricingBundle\Service\BonusPricingManager';
    
    private $memberManager;

    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
        $this->instance->setMemberManager($this->container->get('success.member.member_manager'));
        
        $this->memberManager = $this->container->get('success.member.member_manager');
    }

    public function testGetCurrentBonusPricing()
    {
        $result = $this->instance->getCurrentBonusPricing();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\BonusPricing', $result);
    }

    public function testCalculateBonusForMember()
    {
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->calculateBonusForMember($member);
        print_r($result);
        //die; 	user_1-1@fake.domain 
        
//        $member = $this->memberManager->getMemberByExternalId('user_1-1@fake.domain');
//        $result = $this->instance->calculateBonusForMember($member);
//        var_dump($result);
        
    }
    
    public function testCalculateBaseBonusForMember()
    {
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->calculateMemberBaseBonus($member);
        var_dump($result);
    }
}
