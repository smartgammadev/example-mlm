<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\EventBundle\Entity\EventAccessType;



class LoadAccessTypeData extends AbstractFixture implements OrderedFixtureInterface {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newAccessType1 = new EventAccessType();
        $newAccessType1 -> setName("открытый");
        $manager->persist($newAccessType1);

        $newAccessType2 = new EventAccessType();        
        $newAccessType2 -> setName("для партнеров");
        $manager->persist($newAccessType2);
        
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
