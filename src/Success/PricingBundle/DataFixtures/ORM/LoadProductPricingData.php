<?php

namespace Success\PricingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\PricingBundle\Entity\ProductPricing;

class LoadProductPricingData extends AbstractFixture implements OrderedFixtureInterface
{
    private $fixtrues = [
        [
            'id' => 1,
            'isActive' => true,
            'productName' => 'базовый',
            'productPrice' => 30.00,
        ],
        [
            'id' => 2,
            'isActive' => true,
            'productName' => 'стандарт',
            'productPrice' => 60.00,
        ],
        [
            'id' => 3,
            'isActive' => true,
            'productName' => 'V.I.P.',
            'productPrice' => 90.00,
        ],
    ];
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $metadata = $manager->getClassMetaData('Success\PricingBundle\Entity\ProductPricing');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        
        
        foreach ($this->fixtrues as $fixture) {
            extract($fixture);
            $newProductPricing = new ProductPricing();
            $newProductPricing->setIsActive($isActive);
            $newProductPricing->setId($id);
            $newProductPricing->setProductName($productName);
            $newProductPricing->setProductPrice($productPrice);
            $manager->persist($newProductPricing);
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 20;
    }
}
