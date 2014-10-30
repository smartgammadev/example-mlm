<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseEvent
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\EventBundle\Entity\BaseEventRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"base_event" = "BaseEvent", "webinar_event" = "WebinarEvent"})
 */
class BaseEvent
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
     * @var string
     *
     * @ORM\Column(name="pattern", type="string", length=100)
     */
    private $pattern;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDateTime", type="datetime")
     */
    private $startDateTime;

    
    /**
     * @ORM\ManyToOne(targetEntity="EventType", inversedBy="events")
     * @ORM\JoinColumn(name="event_type_id", referencedColumnName="id")
     */
    private $eventType;

    /**
     * @ORM\ManyToOne(targetEntity="EventAccessType", inversedBy="events")
     * @ORM\JoinColumn(name="access_type_id", referencedColumnName="id")
     */
    private $accessType;
    
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;

    
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
     * @return BaseEvent
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
     * @return BaseEvent
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
     * @return BaseEvent
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
     * Set eventType
     *
     * @param \Success\EventBundle\Entity\EventType $eventType
     * @return BaseEvent
     */
    public function setEventType(\Success\EventBundle\Entity\EventType $eventType = null)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return \Success\EventBundle\Entity\EventType 
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set accessType
     *
     * @param \Success\EventBundle\Entity\EventAccessType $accessType
     * @return BaseEvent
     */
    public function setAccessType(\Success\EventBundle\Entity\EventAccessType $accessType = null)
    {
        $this->accessType = $accessType;

        return $this;
    }

    /**
     * Get accessType
     *
     * @return \Success\EventBundle\Entity\EventAccessType 
     */
    public function getAccessType()
    {
        return $this->accessType;
    }
    
    public function __toString() 
    {
        return $this -> name;
    }

//    /**
//     * Set media
//     *
//     * @param MediaInterface $media
//     * @return BaseEvent
//     */
//    public function setMedia(MediaInterface $media = null)
//    {
//        $this->media = $media;
//
//        return $this;
//    }
//
//    /**
//     * Get media
//     *
//     * @return MediaInterface
//     */
//    public function getMedia()
//    {
//        return $this->media;
//    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return BaseEvent
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }
}