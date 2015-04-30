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
    
    /* @var $memberManager \Success\MemberBundle\Service\MemberManager */
    private $memberManager;
                                  
    
    protected function setUp()
    {
        parent::setUp();
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
        $this->memberManager = $this->container->get('success.member.member_manager');
    }
    
    public function testCopyReferalPricingFromCurrent()
    {
        $result = $this->instance->copyReferalPricingFromCurrent();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
    }
    
    public function testGetCurrentReferalPricing()
    {
        $result = $this->instance->getCurrentReferalPricing();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
    }
    
    public function testAddLevelsToReferalPricing()
    {
        $referalPricing = $this->instance->getCurrentReferalPricing();
        $oldLevels = $referalPricing->getPricingValues()->count();
        
        $result = $this->instance->addLevelsToReferalPricing($referalPricing, 2);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
        
        $newLevels = $result->getPricingValues()->count();
        $this->assertEquals($oldLevels + 2, $newLevels);
    }
    
    public function testRemoveLevelsFromReferalPricing()
    {
        $referalPricing = $this->instance->getCurrentReferalPricing();
        $oldLevels = $referalPricing->getPricingValues()->count();
        
        $result = $this->instance->removeLevelsFromReferalPricing($referalPricing, 2);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\PricingBundle\Entity\ReferalPricing', $result);
        
        $newLevels = $result->getPricingValues()->count();
        $this->assertEquals($oldLevels - 2, $newLevels);
    }
    
    public function testProcessReferalPricingForMember()
    {
        $this->memberManager->resolveMemberByExternalId('fake_mail@fake_domain.fake', '4success.bz@gmail.com');
        $this->memberManager->resolveMemberByExternalId('fake_mail1@fake_domain.fake1', 'fake_mail@fake_domain.fake');
        $member = $this->memberManager->resolveMemberByExternalId('fake_mail2@fake_domain.fake2', 'fake_mail1@fake_domain.fake1');
        
        $this->instance->processReferalPricingForMember($member, 90);
    }
}
