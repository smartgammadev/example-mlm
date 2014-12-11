<?php
namespace Success\EventBundle\Traits;
use Success\NotificationBundle\Service\NotificationManager;
/**
 * Description of SetMemberManagerTrait
 *
 * @author develop1
 */
trait SetNotificationManagerTrait {
    
    /**
     *
     * @var \Success\NotificationBundle\Service\NotificationManager
     */
    private $notificationManager;    

    public function setNotificationManager(NotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;    
    }
    
}
