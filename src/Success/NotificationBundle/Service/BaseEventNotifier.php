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
     * @var \Success\NotificationBundle\Service\SMSManager
     */    
    private $SMSManager;
    
    /**
     * @param type $mailer
     * @param \Success\SettingsBundle\Service\SettingsManager $settingsManager
     * @param \Success\PlaceholderBundle\Service\PlaceholderManager $placeholderManager
     * @param \Success\NotificationBundle\Service\SMSManager
     * 
     */            
    public function __construct($mailer, $settingsManager, $SMSManager) {
        $this->mailer = $mailer;
        $this->settingsManager = $settingsManager;
        $this->SMSManager = $SMSManager;
    }
    
    /**
     * @param EmailNotification $notification
     * @param string $templateName
     * @param array ('name'=>'value')
     * @return boolean 
     */    
    public function sendEmailNotification(EmailNotification $notification, $templateName, array $params)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_String());            
        $msgTemplate = $this->settingsManager->getSettingValue($templateName);
        
        $msgBody = $twig->render($msgTemplate, $params);

        $message = \Swift_Message::newInstance()
            ->setSubject('Test Email')
            ->setFrom('rregion1292@gmail.com')
            ->setTo($notification->getDestination())
            ->setBody($msgBody);
        
            echo "Sending mail to ".$notification->getDestination();echo PHP_EOL;
            
            if ($this->mailer->send($message)!==0){
                $notification->setIsSent(true);
                $notification->setIsFailed(false);
            } else{
                $notification->setIsSent(true);
                $notification->setIsFailed(true);                
            }
            $this->em->flush();
    }
    
    /**
     * @param SMSNotification $notification
     * @param string $templateName
     * @param array ('name'=>'value')
     * @return boolean 
     */    
    public function sendSMSNotification(SMSNotification $notification, $templateName, array $params)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_String());
        $msgTemplate = $this->settingsManager->getSettingValue($templateName);
        $msgBody = $twig->render($msgTemplate, $params);

        echo "Sending SMS to ".$notification->getDestination();echo PHP_EOL;
        $msgStatus = $this->SMSManager->msgSend($notification->getDestination(), $msgBody);        
        
        if (!$msgStatus==0){
            $notification->setIsSent(true);
            $notification->setMsgId($msgStatus);
            
            
            //$notification->addLog($logs)
        }else{
            $notification->setIsSent(true);
            //$notification->setIsFailed(true);          
        }
        $this->em->flush();
    }
}
