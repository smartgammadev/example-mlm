<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Success\EventBundle\Entity\EventType;

class EventTypeFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $eventNames = ["вводный вебинар", "стартовый тренинг"];
        foreach ($eventNames as $index => $eventName) {
            $newEventType = new EventType();
            $newEventType->setName($eventName);
            $manager->persist($newEventType);
            $this->setReference('SuccessEventType-'.$index, $newEventType);
        }
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}