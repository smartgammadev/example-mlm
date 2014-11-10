<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Success\EventBundle\Entity\EventAccessType;

class EventAccessTypeFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $eventAccessTypeNames = ["открытый", "для партнеров"];
        foreach ($eventAccessTypeNames as $index => $eventName) {
            $newAccessType = new EventAccessType();
            $newAccessType->setName($eventName);
            $manager->persist($newAccessType);
            $this->setReference('SuccessEventAccessType-'.$index, $newAccessType);
        }
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 4;
    }
}
