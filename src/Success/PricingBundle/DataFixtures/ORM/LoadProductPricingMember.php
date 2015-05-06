<?php

namespace Success\PricingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\PricingBundle\Entity\ProductPricing;

class LoadProductPricingMember extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $members = $manager->getRepository('SuccessMemberBundle:Member')->findAll();
        $dateTime = new \DateTime();
        foreach ($members as $member) {
            $newProductPricingMember = new \Success\PricingBundle\Entity\ProductPricingMember();
            $productPricing = $manager->getRepository('SuccessPricingBundle:ProductPricing')->findOneBy(['productName' => 'базовый']);
            $newProductPricingMember->setMember($member);
            $newProductPricingMember->setProductPricing($productPricing);
            $newProductPricingMember->setAssignDate($dateTime);
            $newProductPricingMember->setPricePaid(30);
            $manager->persist($newProductPricingMember);
        }
        $manager->flush();
    }
    
    
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 30;
    }        
}
