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

    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
    }

    public function testGetCurrentBonusPricing()
    {
        $result = $this->instance->getCurrentBonusPricing();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\BonusPricing', $result);
    }
    
    public function testCopyBonusPricingFromCurrent()
    {
        $result = $this->instance->copyBonusPricingFromCurrent();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\BonusPricing', $result);
    }
}
