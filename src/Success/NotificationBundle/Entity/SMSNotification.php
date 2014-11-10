<?php
namespace Success\NotificationBundle\Entity;

use Success\NotificationBundle\Entity\Notification;
use Doctrine\ORM\Mapping as ORM;

/**
 * SMSNotification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\NotificationBundle\Entity\NotificationRepository")
 */
class SMSNotification extends Notification 
{    
    /**
     * @ORM\Column(name="params", type="json_array")
     * 
     */
    private $params;
    
    /**
     * @var string
     * @ORM\Column(name="msg_id", type="string", length=255, nullable=true)
     */
    private $msgId;
    

    /**
     * Set params
     *
     * @param array $params
     * @return SMSNotification
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

    /**
     * Set msgId
     *
     * @param string $msgId
     * @return SMSNotification
     */
    public function setMsgId($msgId)
    {
        $this->msgId = $msgId;

        return $this;
    }

    /**
     * Get msgId
     *
     * @return string 
     */
    public function getMsgId()
    {
        return $this->msgId;
    }
}
