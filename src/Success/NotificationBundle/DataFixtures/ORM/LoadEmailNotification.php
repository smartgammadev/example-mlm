<?php

namespace Success\NotificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Success\NotificationBundle\Entity\EmailNotification;


class LoadEmailNotification extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $notifyEmail1 = new EmailNotification();
        $notifyEmail1->setName('userSignUpEmailMessage');
        $notifyEmail1->setDestination('testCommand@mail.com');
        $notifyEmail1->setIsSent(0);
        $notifyEmail1->setIsFailed(0);
        $notifyEmail1->setStartDateTime(new \DateTime());
        $notifyEmail1->setParams( array( "user_email" => "user@mail", "user_first_name" => "uName") );
        $manager->persist($notifyEmail1);

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
