<?php
namespace Success\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Success\EventBundle\Entity\WebinarEvent;
//use Success\EventBundle\Entity\EventAccessType;
use Success\EventBundle\Entity\EventRepeat;


class LoadWebinarData extends AbstractFixture implements OrderedFixtureInterface {
    
    const OPEN_ACCESS_TYPE_NAME = 'открытый';
    const INTRODUCTION_TYPE_NAME = 'вводный вебинар';


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $newWebinar = new WebinarEvent();
        $newWebinar->setName('Test webinar event');
        $newWebinar->setDescription('this record is for testing');
        $newWebinar->setUrl('http://myownconference.com');
        $newWebinar->setPassword('pass');
        $newWebinar->setPattern('pattern');
        
        $startDateTime = new \DateTime();
        $startDateTime->modify('-1 day');        
        $newWebinar->setStartDateTime($startDateTime);
        
        $eventType = $manager->getRepository('SuccessEventBundle:EventType')->findOneBy(['name' => self::INTRODUCTION_TYPE_NAME]);
        $newWebinar->setEventType($eventType);
        
        $accessType = $manager->getRepository('SuccessEventBundle:EventAccessType')->findOneBy(['name' => self::OPEN_ACCESS_TYPE_NAME]);
        $newWebinar->setAccessType($accessType);                
        
        $webinarImage = $manager->getRepository('ApplicationSonataMediaBundle:Media')->findOneBy(['name' => 'webinar_image']);        
        $newWebinar->setMedia($webinarImage);
        
        $repeatWebinar = new WebinarEvent();
        
        $repeatWebinar->setName('Test repeatable webinar event');
        $repeatWebinar->setDescription('this record is for testing');
        $repeatWebinar->setUrl('http://myownconference.com');
        $repeatWebinar->setPassword('pass');
        $repeatWebinar->setPattern('pattern');
        $repeatWebinar->setStartDateTime($startDateTime);
        $repeatWebinar->setEventType($eventType);
        $repeatWebinar->setAccessType($accessType);
        $repeatWebinar->setMedia($webinarImage);
        
        $eventRepat = new EventRepeat();
        $repeatEnd = clone $startDateTime;
        $repeatEnd->modify('+1 year');
        
        $eventRepat->setEndDateTime($repeatEnd);
        $eventRepat->setRepeatType('D');
        $eventRepat->setRepeatInterval(1);
        //{"1":true,"2":true,"3":true,"4":true,"5":true,"6":true,"0":true}
        $repeatDays = ["0" => true, "1" => true, "2" => true, "3" => true, "4" => true, "5" => true, "6" => true];
        $eventRepat->setRepeatDays($repeatDays);
        $manager->persist($eventRepat);
        
        $repeatWebinar->setEventRepeat($eventRepat);
        
        
        $manager->persist($newWebinar);
        $manager->persist($repeatWebinar);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }    
}
