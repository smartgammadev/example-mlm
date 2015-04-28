<?php

namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\PricingBundle\Entity\ProductPricing;

class LoadProductPricingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newProductPricing = new ProductPricing();
        $newProductPricing->setIsActive(true);
        $newProductPricing->setProductName('default');
        $newProductPricing->setProductPrice(30);
        $manager->persist($newProductPricing);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
