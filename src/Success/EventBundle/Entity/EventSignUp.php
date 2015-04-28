<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventSignUp
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EventSignUp
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
     * @var \DateTime
     *
     * @ORM\Column(name="signUpDateTime", type="datetime")
     */
    private $signUpDateTime;

    /**
     * @var  \Success\MemberBundle\Entity\Member
     * @ORM\ManyToOne(targetEntity="Success\MemberBundle\Entity\Member", inversedBy="signUpEvents")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @var \Success\EventBundle\Entity\BaseEvent
     * @ORM\ManyToOne(targetEntity="BaseEvent", inversedBy="signUps")
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
     * Set signUpDateTime
     *
     * @param \DateTime $signUpDateTime
     * @return EventSignUp
     */
    public function setSignUpDateTime($signUpDateTime)
    {
        $this->signUpDateTime = $signUpDateTime;

        return $this;
    }

    /**
     * Get signUpDateTime
     *
     * @return \DateTime
     */
    public function getSignUpDateTime()
    {
        return $this->signUpDateTime;
    }

    /**
     * Set member
     *
     * @param \Success\MemberBundle\Entity\Member $member
     * @return EventSignUp
     */
    public function setMember(\Success\MemberBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Success\MemberBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set event
     *
     * @param \Success\EventBundle\Entity\BaseEvent $event
     * @return EventSignUp
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
}
