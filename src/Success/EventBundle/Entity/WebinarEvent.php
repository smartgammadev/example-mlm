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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */    
    private $description;
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
     * Set description
     *
     * @param string $description
     * @return WebinarEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
}
