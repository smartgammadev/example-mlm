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
    /**
     * @ORM\Column(name="params", type="json_array")
     * 
     */
    private $params;
    

    /**
     * Set params
     *
     * @param array $params
     * @return EmailNotification
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Get params
     *
     * @return array 
     */
    public function getParams()
    {
        return $this->params;
    }
}
