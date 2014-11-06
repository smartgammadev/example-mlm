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
 * Description of BaseEventNotifier
 *
 * @author develop1
 */
class BaseEventNotifier {
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     * @var type 
     */
    private $mailer;
    
    /**
     * @var \Success\SettingsBundle\Entity\Setting
     */
    private $settingsManager;
    
    /**
     * @param type $mailer
     * @param \Success\SettingsBundle\Service\SettingsManager $settingsManager
     * @param \Success\PlaceholderBundle\Service\PlaceholderManager $placeholderManager
     * 
     */            
    public function __construct($mailer, $settingsManager) {
        $this->mailer = $mailer;
        $this->settingsManager = $settingsManager;
    }
    
    /**
     * @param EmailNotification $notification
     * @param string $template
     * @return boolean 
     */    
    public function sendEmailNotification(EmailNotification $notification, $templateName)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_String());            
        $msgTemplate = $this->settingsManager->getSettingValue($templateName);
        
        $msgBody = $twig->render($msgTemplate, array());

        echo $msgBody;
        $message = \Swift_Message::newInstance()
            ->setSubject('Test Email')
            ->setFrom('rregion1292@gmail.com')
            ->setTo($notification->getDestination())
            ->setBody($msgBody);
            
            if ($this->mailer->send($message)!==0){
                $notification->setIsSent(true);
                $notification->setIsFailed(false);
            } else{
                $notification->setIsSent(true);
                $notification->setIsFailed(true);                
            }
            $this->em->flush();
    }

}
