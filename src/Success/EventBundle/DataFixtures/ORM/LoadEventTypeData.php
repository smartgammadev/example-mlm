<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\EventBundle\Entity\EventType;
//use Acme\HelloBundle\Entity\User;

class LoadEventTypeData implements FixtureInterface {
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newEvent1 = new EventType();        
        $newEvent1 -> setName("вводный вебинар");
        $manager->persist($newEvent1);

        $newEvent2 = new EventType();        
        $newEvent2 -> setName("стартовый тренинг");
        $manager->persist($newEvent2);
        
        $manager->flush();
    }
}
