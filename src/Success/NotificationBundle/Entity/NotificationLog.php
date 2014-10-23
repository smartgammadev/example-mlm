<?php

namespace Success\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationLog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\NotificationBundle\Entity\NotificationLogRepository")
 */
class NotificationLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actionDate", type="datetime")
     */
    private $actionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="providerName", type="string", length=50)
     */
    private $providerName;

    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="logs")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    private $notification;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return NotificationLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set actionDate
     *
     * @param \DateTime $actionDate
     * @return NotificationLog
     */
    public function setActionDate($actionDate)
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    /**
     * Get actionDate
     *
     * @return \DateTime 
     */
    public function getActionDate()
    {
        return $this->actionDate;
    }

    /**
     * Set providerName
     *
     * @param string $providerName
     * @return NotificationLog
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;

        return $this;
    }

    /**
     * Get providerName
     *
     * @return string 
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * Set notification
     *
     * @param \Success\NotificationBundle\Entity\Notification $notification
     * @return NotificationLog
     */
    public function setNotification(\Success\NotificationBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Success\NotificationBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
