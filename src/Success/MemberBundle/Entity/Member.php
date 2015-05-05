<?php

namespace Success\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation\Tree as Tree;

/**
 * Member
 * @Tree(type="nested")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\MemberBundle\Entity\Repository\MemberRepository")
 */
class Member implements UserInterface, \Serializable
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="Member", mappedBy="sponsor")
     */
    private $referals;
    
    
    /**
     * @Gedmo\Mapping\Annotation\TreeParent
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="referals")
     * @ORM\JoinColumn(name="sponsor_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $sponsor;
    
    /**
     * @Gedmo\Mapping\Annotation\TreeLeft
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\Mapping\Annotation\TreeRight
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $rgt;
    
    public function getPassword()
    {
    }
    
    public function getSalt()
    {
    }
    
    public function getUsername()
    {
        return $this->externalId;
    }
    
    public function getRoles()
    {
        return ($this->referals->count() > 0 ? ['ROLE_4SUCCESS_SPONSOR'] : ['ROLE_4SUCCESS_USER']);
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
     * @return \Doctrine\Common\Collections\ArrayCollection
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
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->externalId,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->externalId,
        ) = unserialize($serialized);
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Member
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Member
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }
}
