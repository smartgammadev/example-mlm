<?php

namespace Success\MemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MemberData
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Success\MemberBundle\Entity\MemberDataRepository")
 */
class MemberData
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
     * @ORM\Column(name="member_data", type="text")
     */
    private $memberData;
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="data")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Success\PlaceholderBundle\Entity\BasePlaceholder")
     * @ORM\JoinColumn(name="placeholder_id", referencedColumnName="id")
     */
    private $placeholder;

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
     * Set memberData
     *
     * @param string $memberData
     * @return MemberData
     */
    public function setMemberData($memberData)
    {
        $this->memberData = $memberData;

        return $this;
    }

    /**
     * Get memberData
     *
     * @return string 
     */
    public function getMemberData()
    {
        return $this->memberData;
    }

    /**
     * Set member
     *
     * @param \Success\MemberBundle\Entity\Member $member
     * @return MemberData
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
     * Set placeholder
     *
     * @param \Success\PlaceholderBundle\Entity\BasePlaceholder $placeholder
     * @return MemberData
     */
    public function setPlaceholder(\Success\PlaceholderBundle\Entity\BasePlaceholder $placeholder = null)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return \Success\PlaceholderBundle\Entity\BasePlaceholder 
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
}
