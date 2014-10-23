<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\EventBundle\Entity\EventAccessType;



class LoadAccessTypeData implements FixtureInterface {
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
}
