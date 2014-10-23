<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EventAccessType
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\EventBundle\Entity\EventAccessTypeRepository")
 */
class EventAccessType
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="BaseEvent", mappedBy="accessType")
     */
    private $events;

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
     * @return EventAccessType
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
    
    
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * Add events
     *
     * @param \Success\EventBundle\Entity\BaseEvent $events
     * @return EventAccessType
     */
    public function addEvent(\Success\EventBundle\Entity\BaseEvent $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Success\EventBundle\Entity\BaseEvent $events
     */
    public function removeEvent(\Success\EventBundle\Entity\BaseEvent $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
}
