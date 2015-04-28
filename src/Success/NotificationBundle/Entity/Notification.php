<?php

namespace Success\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\NotificationBundle\Entity\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"email_notification" = "EmailNotification", "sms_notification" = "SMSNotification"})
 */
class Notification
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="destination", type="string", length=255)
     */
    private $destination;

    /**
     * @var \DateTime
     * @ORM\Column(name="startDateTime", type="datetime")
     */
    private $startDateTime;

    /**
     * @ORM\OneToMany(targetEntity="NotificationLog", mappedBy="notification")
     */
    private $logs;
    
    /**
     * @ORM\Column(name="is_sent", type="boolean")
     */
    private $isSent;

    /**
     * @ORM\Column(name="is_failed", type="boolean", nullable=true)
     */    
    private $isFailed;
    
    public function __construct() {
        $this -> logs = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     * @return Notification
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pattern
     *
     * @param string $pattern
     * @return Notification
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * Get pattern
     *
     * @return string 
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set startDateTime
     *
     * @param \DateTime $startDateTime
     * @return Notification
     */
    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;
        return $this;
    }

    /**
     * Get startDateTime
     *
     * @return \DateTime 
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Add logs
     *
     * @param \Success\NotificationBundle\Entity\NotificationLog $logs
     * @return Notification
     */
    public function addLog(\Success\NotificationBundle\Entity\NotificationLog $logs)
    {
        $this->logs[] = $logs;
        return $this;
    }

    /**
     * Remove logs
     *
     * @param \Success\NotificationBundle\Entity\NotificationLog $logs
     */
    public function removeLog(\Success\NotificationBundle\Entity\NotificationLog $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Set destination
     *
     * @param string $destination
     * @return Notification
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set isSent
     *
     * @param boolean $isSent
     * @return Notification
     */
    public function setIsSent($isSent)
    {
        $this->isSent = $isSent;

        return $this;
    }

    /**
     * Get isSent
     *
     * @return boolean 
     */
    public function getIsSent()
    {
        return $this->isSent;
    }

    /**
     * Set isFailed
     *
     * @param boolean $isFailed
     * @return Notification
     */
    public function setIsFailed($isFailed)
    {
        $this->isFailed = $isFailed;

        return $this;
    }

    /**
     * Get isFailed
     *
     * @return boolean 
     */
    public function getIsFailed()
    {
        return $this->isFailed;
    }
}
