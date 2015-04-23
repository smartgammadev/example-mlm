<?php

namespace Success\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\MemberBundle\Entity\MemberRepository")
 */
class Member implements UserInterface
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
     * @ORM\Column(name="external_id", type="string", unique=true, length=255)
     */
    private $externalId;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="MemberData", mappedBy="member")
     */
    private $data;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Success\EventBundle\Entity\EventSignUp", mappedBy="member")
     */
    private $signUpEvents;

    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="sponsor")
     */
    private $referals;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="referals")
     * @ORM\JoinColumn(name="sponsor_id", referencedColumnName="id")
     */
    private $sponsor;

    public function getPassword()
    {
        ;
    }
    
    public function getSalt()
    {
        ;
    }
    
    public function getUsername()
    {
        return $this->externalId;
    }
    
    public function getRoles()
    {
        return ($this->referals->count() > 0 ? ['ROLE_SPONSOR'] : ['ROLE_USER']);
    }
    
    public function eraseCredentials()
    {
        ;
    }

    /**
     * Get id
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
        $this->signsUp = new ArrayCollection();
        $this->referals = new ArrayCollection();
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

    /**
     * Add signUpEvents
     *
     * @param \Success\EventBundle\Entity\EventSignUp $signUpEvents
     * @return Member
     */
    public function addSignUpEvent(\Success\EventBundle\Entity\EventSignUp $signUpEvents)
    {
        $this->signUpEvents[] = $signUpEvents;

        return $this;
    }

    /**
     * Remove signUpEvents
     *
     * @param \Success\EventBundle\Entity\EventSignUp $signUpEvents
     */
    public function removeSignUpEvent(\Success\EventBundle\Entity\EventSignUp $signUpEvents)
    {
        $this->signUpEvents->removeElement($signUpEvents);
    }

    /**
     * Get signUpEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSignUpEvents()
    {
        return $this->signUpEvents;
    }

    public function __toString()
    {
        return $this->externalId;
    }

    /**
     * Add referals
     *
     * @param \Success\MemberBundle\Entity\Member $referals
     * @return Member
     */
    public function addReferal(\Success\MemberBundle\Entity\Member $referals)
    {
        $this->referals[] = $referals;

        return $this;
    }

    /**
     * Remove referals
     *
     * @param \Success\MemberBundle\Entity\Member $referals
     */
    public function removeReferal(\Success\MemberBundle\Entity\Member $referals)
    {
        $this->referals->removeElement($referals);
    }

    /**
     * Get referals
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferals()
    {
        return $this->referals;
    }

    /**
     * Set sponsor
     * @param \Success\MemberBundle\Entity\Member $sponsor
     * @return Member
     */
    public function setSponsor(\Success\MemberBundle\Entity\Member $sponsor = null)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor
     * @return \Success\MemberBundle\Entity\Member
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }
}
