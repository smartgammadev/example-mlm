<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebinarEvent
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\EventBundle\Entity\WebinarEventRepository")
 */
class WebinarEvent extends BaseEvent
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private  $url;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private  $password;


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
     * Set url
     *
     * @param string $url
     * @return WebinarEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return WebinarEvent
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @var \Success\EventBundle\Entity\EventType
     */
    private $eventType;


    /**
     * Set eventType
     *
     * @param \Success\EventBundle\Entity\EventType $eventType
     * @return WebinarEvent
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
     * @var \Application\Sonata\MediaBundle\Entity\Media
     */
    private $media;

    /**
     * @param MediaInterface $media
     */
    public function setMedia(MediaInterface $media)
    {
        $this->media = $media;
    }

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }
}
