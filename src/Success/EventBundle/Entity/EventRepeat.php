<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventRepeat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\EventBundle\Entity\EventRepeatRepository")
 * @ORM\HasLifecycleCallbacks
 */
class EventRepeat
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
     * @ORM\Column(name="repeatType", type="string", length=1)
     */
    private $repeatType;

    /**
     * @var integer
     *
     * @ORM\Column(name="repeatInterval", type="integer")
     */
    private $repeatInterval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDateTime", type="datetime")
     */
    private $endDateTime;

    
    /**
     * @var type 
     * @ORM\OneToOne(targetEntity="BaseEvent", inversedBy="eventRepeat")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;
    
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
     * Set repeatType
     *
     * @param string $repeatType
     * @return EventRepeat
     */
    public function setRepeatType($repeatType)
    {
        $this->repeatType = $repeatType;
        return $this;
    }

    /**
     * Get repeatType
     *
     * @return string 
     */
    public function getRepeatType()
    {
        return $this->repeatType;
    }

    /**
     * Set repeatInterval
     *
     * @param integer $repeatInterval
     * @return EventRepeat
     */
    public function setRepeatInterval($repeatInterval)
    {
        $this->repeatInterval = $repeatInterval;

        return $this;
    }

    /**
     * Get repeatInterval
     *
     * @return integer 
     */
    public function getRepeatInterval()
    {
        return $this->repeatInterval;
    }

    /**
     * Set endDateTime
     *
     * @param \DateTime $endDateTime
     * @return EventRepeat
     */
    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * Get endDateTime
     *
     * @return \DateTime 
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Set event
     *
     * @param \Success\EventBundle\Entity\BaseEvent $event
     * @return EventRepeat
     */
    public function setEvent(\Success\EventBundle\Entity\BaseEvent $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Success\EventBundle\Entity\BaseEvent 
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    public function __toString() {        
        $endDate = $this->endDateTime->format('d/m/Y H:i:s');
        return "$this->repeatType($this->repeatInterval, $endDate)";
    }
}
