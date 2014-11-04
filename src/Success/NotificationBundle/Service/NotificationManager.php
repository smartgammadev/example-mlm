<?php
namespace Success\NotificationBundle\Service;

use Success\NotificationBundle\Entity\EmailNotification;
use Success\NotificationBundle\Entity\SMSNotification;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotifyManager
 *
 * @author develop1
 */
class NotificationManager 
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     * @param \DateTime $datetime
     * @param string $name 
     * @param string $email
     * @return void
     */
    public function createEmailNotification(\DateTime $datetime,$email,$name)
    {
        $notification = new EmailNotification();
        $notification->setName($name);
        $notification->setStartDateTime($datetime);
        $notification->setDestination($email);
        $this->em->persist($notification);
        $this->em->flush();
    }

    /**
     * @param \DateTime $datetime
     * @param string $name 
     * @param string $phone
     * @return void
     */    
    public function createSMSNotification(\DateTime $datetime,$phone,$name)
    {
        $notification = new SMSNotification();
        $notification->setName($name);
        $notification->setStartDateTime($datetime);
        $notification->setDestination($phone);
        $this->em->persist($notification);
        $this->em->flush();
    }

}