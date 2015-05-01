<?php

namespace Success\TreasureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\MemberBundle\Entity\Member;

/**
 * AccountBalance
 *
 * @ORM\Table(name="t_account_balance")
 * @ORM\Entity
 */
class AccountBalance
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
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;


    /**
     * @ORM\ManyToOne(targetEntity="\Success\MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $member;
    

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
     * Set amount
     *
     * @param string $amount
     * @return AccountBalance
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set member
     *
     * @param \Success\MemberBundle\Entity\Member $member
     * @return AccountBalance
     */
    public function setMember(Member $member)
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
}
