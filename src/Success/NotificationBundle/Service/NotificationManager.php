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
     *
     * @var \Success\NotificationBundle\Service\BaseEventNotifier
     */
    private $baseEventNotifier;
    
    /**
     * @var \Success\PlaceholderBundle\Service\PlaceholderManager
     */
    private $placeholderManager;
    
    public function __construct($baseEventNotifier, $placeholderManager) {
        $this->baseEventNotifier = $baseEventNotifier;
        $this->placeholderManager = $placeholderManager;
    }


    /**
     * @param \DateTime $datetime
     * @param string $email 
     * @param string $templateName
     * @return void
     */
    public function createEmailNotification(\DateTime $datetime, $email, $templateName)
    {
                
        $notification = new EmailNotification();
        $notification->setName($templateName);
        $notification->setStartDateTime($datetime);
        $notification->setDestination($email);
        $notification->setIsSent(false);
        $notification->setIsFailed(false);
        
        $placeholders = $this->placeholderManager->getPlaceholdersFromSession();
        $notification->setParams($placeholders);
        
        $this->em->persist($notification);
        $this->em->flush();
    }
    
    /**
     * @param \DateTime $datetime
     * @param string $phone
     * @param string $templateName
     * @return void
     */    
    public function createSMSNotification(\DateTime $datetime,$phone,$templateName)
    {
        $notification = new SMSNotification();
        $notification->setName($templateName);
        $notification->setStartDateTime($datetime);
        $notification->setDestination($phone);
        $notification->setIsSent(false);
        $notification->setIsFailed(false);        
        $this->em->persist($notification);
        $this->em->flush();
    }
    
    public function processEmailNotifications()
    {
        /**
         * @var \Success\NotificationBundle\Entity\NotificationRepository
         */
        $repo = $this->em->getRepository('SuccessNotificationBundle:EmailNotification');
        $notifications = $repo->getEmailNotificationsToSend();
        
        foreach ($notifications as $notification){
            $this->baseEventNotifier->sendEmailNotification($notification, $notification->getName(), $notification->getParams());
        }
           
    }
    
    public function processSMSNotifications()
    {
        /**
         * @var \Success\NotificationBundle\Entity\NotificationRepository
         */
        $repo = $this->em->getRepository('SuccessNotificationBundle:SMSNotification');
        $notifications = $repo->getSMSNotificationsToSend();        
        foreach ($notifications as $notification){
            $this->baseEventNotifier->sendSMSNotification($notification, $notification->getName(), $notification->getParams());
        }
           
    }
    
}