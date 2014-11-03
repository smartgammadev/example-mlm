<?php

namespace Success\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\MemberBundle\Entity\MemberRepository")
 */
class Member
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
     * @ORM\Column(name="external_id", type="string", unique=true, length=255)
     */
    private $externalId;

    /**
     * @ORM\OneToMany(targetEntity="MemberData", mappedBy="member")
     */
    private $data;


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
     * Set externalId
     *
     * @param string $externalId
     * @return Member
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return string 
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = new ArrayCollection();
    }

    /**
     * Add data
     *
     * @param \Success\MemberBundle\Entity\MemberData $data
     * @return Member
     */
    public function addDatum(\Success\MemberBundle\Entity\MemberData $data)
    {
        $this->data[] = $data;

        return $this;
    }

    /**
     * Remove data
     *
     * @param \Success\MemberBundle\Entity\MemberData $data
     */
    public function removeDatum(\Success\MemberBundle\Entity\MemberData $data)
    {
        $this->data->removeElement($data);
    }

    /**
     * Get data
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getData()
    {
        return $this->data;
    }
}
