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
        $this->memberManager = $this->container->get('success.member.member_manager');
    }
    
    public function testGetActiveProductPricingByName()
    {
        $productPricing = $this->instance->getActiveProductPricingByName('V.I.P.');
        $this->assertNotNull($productPricing);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricing', $productPricing);
        $this->assertEquals('V.I.P.', $productPricing->getProductName());
        $this->assertTrue($productPricing->getIsActive());
    }

    public function testGetNotFoundProductPricingByName()
    {
        $productPricing = $this->instance->getActiveProductPricingByName('not_existing_pricing');
        $this->assertNull($productPricing);
    }
    
    public function testAssignProductPricingToMember()
    {
        $productPricing = $this->instance->getActiveProductPricingByName('V.I.P.');
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->assignProductPricingToMember($productPricing, $member);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricingMember', $result);
    }
    
    public function testGetCurrentProductPricingForMember()
    {
        $member = $this->memberManager->getMemberByExternalId('4success.bz@gmail.com');
        $result = $this->instance->getCurrentProductPricingForMember($member);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ProductPricingMember', $result);
    }
}
