<?php
namespace Success\PricingBundle\Tests\Services;
          
use Gamma\PhpUnit\Tester\Test\ServiceTest;

class ReferalPricingManangerTest extends ServiceTest
{
    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\PricingBundle\Service\ReferalPricingManager';
                                  
    
    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
    }
    
    public function testCreateNewReferalPricing()
    {
        $result = $this->instance->createNewReferalPricing('default');
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
        $this->assertEquals($result->getName(), 'default');
    }
    
    public function testGetCurrentReferalPricing()
    {
        $this->instance->createNewReferalPricing('new pricing');
        $result = $this->instance->getCurrentReferalPricing();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
        $this->assertEquals($result->getName(), 'new pricing');
    }
}
