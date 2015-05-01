<?php

namespace Success\TreasureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\MemberBundle\Entity\Member;

/**
 * AccountOperation
 *
 * @ORM\Table(name="t_account_operation")
 * @ORM\Entity(repositoryClass="Success\TreasureBundle\Entity\Repository\AccountOperationRepository")
 */
class AccountOperation
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
     * @ORM\Column(name="date_operation", type="datetime", nullable=false)
     */
    private $dateOperation;
    
    /**
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(name="sub_account", type="string", nullable=false)
     */
    
    private $subAccount;
    
    /**
     * @ORM\ManyToOne(targetEntity="\Success\MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $member;

    

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     * @return AccountOperation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set member
     *
     * @param \Success\MemberBundle\Entity\Member $member
     * @return AccountOperation
     */
    public function setMember(Member $member = null)
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
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     * @return AccountOperation
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set subAccount
     *
     * @param string $subAccount
     * @return AccountOperation
     */
    public function setSubAccount($subAccount)
    {
        $this->subAccount = $subAccount;

        return $this;
    }

    /**
     * Get subAccount
     *
     * @return string 
     */
    public function getSubAccount()
    {
        return $this->subAccount;
    }
}
