<?php
namespace Success\NotificationBundle\Entity;

use Success\NotificationBundle\Entity\Notification;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmailNotification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\NotificationBundle\Entity\NotificationRepository")
 */
class EmailNotification extends Notification 
{
    
}
