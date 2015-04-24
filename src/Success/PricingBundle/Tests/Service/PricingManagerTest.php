<?php
namespace Success\PricingBundle\Tests\Services;
          
use Gamma\PhpUnit\Tester\Test\ServiceTest;

class PricingManangerTest extends ServiceTest
{
    /**
     * Target class name for the instance creation
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\PricingBundle\Service\PricingManager';
                                  
    
    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
    }
    
    public function testCreateNewPricing()
    {
        $result = $this->instance->createNewPricing('default');
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\Pricing', $result);
        $this->assertEquals($result->getName(), 'default');
    }
    
    public function testGetCurrentPricing()
    {
        $this->instance->createNewPricing('new pricing');
        $result = $this->instance->getCurrentPricing();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\Pricing', $result);
        $this->assertEquals($result->getName(), 'new pricing');
    }
}
