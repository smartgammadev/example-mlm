<?php

namespace Success\PricingBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;

class ProductPricingManagerTest extends ServiceTest
{

    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\PricingBundle\Service\ProductPricingManager';
    
    private $memberManager;

    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
        $this->instance->setAccountManager($this->container->get('success.treasure.account_manager'));
        $this->instance->setMemberManager($this->container->get('success.member.member_manager'));
        $this->memberManager = $this->container->get('success.member.member_manager');
    }
    
    public function testGetActiveByName()
    {
        $productPricing = $this->instance->getActiveByName('V.I.P.');
        $this->assertNotNull($productPricing);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricing', $productPricing);
        $this->assertEquals('V.I.P.', $productPricing->getProductName());
        $this->assertTrue($productPricing->getIsActive());
    }

    public function testGetNotFoundByName()
    {
        $productPricing = $this->instance->getActiveByName('not_existing_pricing');
        $this->assertNull($productPricing);
    }
    

    public function testGetActivePricings()
    {
        $result = $this->instance->getActivePricings();
        $this->assertNotNull($result);
        $this->assertInternalType('array', $result);
        $this->assertEquals(3, count($result));
        
        $first = $result[0];
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricing',$first);
    }
       
    public function testCheckIfCanBeAssignedToMemberTrue()
    {
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $productPricing = $this->instance->getActiveByName('V.I.P.');
        $result = $this->instance->checkIfCanBeAssignedToMember($productPricing, $member);
        $this->assertNotNull($result);
        $this->assertTrue($result);
    }

    public function testCheckIfCanBeAssignedToMemberFalse()
    {
        $member = $this->memberManager->getMemberByExternalId('user_1-3@fake.domain');
        $productPricing = $this->instance->getActiveByName('V.I.P.');
        try {
            $result = $this->instance->checkIfCanBeAssignedToMember($productPricing, $member);
        } catch (\Exception $ex) {
            $this->assertFalse(isset($result));
            $this->assertInstanceOf('Success\TreasureBundle\Exception\NotEnoughAmountException', $ex);
        }
    }
    
    public function testProcessForMember()
    {
        $productPricing = $this->instance->getActiveByName('V.I.P.');
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->processForMember($productPricing, $member);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricingMember', $result);
    }
    
    public function testGetCurrentForMember()
    {
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->getCurrentForMember($member);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricingMember', $result);
    }
}
