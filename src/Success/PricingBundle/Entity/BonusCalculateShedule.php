<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\JobQueueBundle\Entity\Job;

/**
 * BonusCalculateShedule
 *
 * @ORM\Table(name="p_bonus_calculate_shedule")
 * @ORM\Entity
 */
class BonusCalculateShedule
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
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     *
     * @ORM\Column(name="calculation_days", type="integer")
     */
    private $calculationDays;

    /**
     * @var boolean
     * @ORM\Column(name="is_processed", type="boolean")
     */
    private $isProcessed;

    /**
     * @var boolean
     * @ORM\Column(name="auto_recreate", type="boolean")
     */
    private $autoRecreate;
    
    /**
     * @var boolean
     * @ORM\Column(name="is_approved", type="boolean")
     */
    private $isApproved = false;

    /**
     * @var \JMS\JobQueueBundle\Entity\Job
     * @ORM\OneToOne(targetEntity="JMS\JobQueueBundle\Entity\Job", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     **/
    private $job;
    
    
    /**
     * @var array
     * @ORM\Column(name="calcultaion_result", type="json_array", nullable=true)
     */
    private $calculationResult;
    
    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return BonusCalculateShedule
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set isProcessed
     *
     * @param boolean $isProcessed
     * @return BonusCalculateShedule
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;

        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return boolean
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }

    /**
     * Set job
     *
     * @param \JMS\JobQueueBundle\Entity\Job $job
     * @return BonusCalculateShedule
     */
    public function setJob(Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \JMS\JobQueueBundle\Entity\Job
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set calculationDays
     *
     * @param integer $calculationDays
     * @return BonusCalculateShedule
     */
    public function setCalculationDays($calculationDays)
    {
        $this->calculationDays = $calculationDays;

        return $this;
    }

    /**
     * Get calculationDays
     *
     * @return integer
     */
    public function getCalculationDays()
    {
        return $this->calculationDays;
    }

    /**
     * Set autoRecreate
     *
     * @param boolean $autoRecreate
     * @return BonusCalculateShedule
     */
    public function setAutoRecreate($autoRecreate)
    {
        $this->autoRecreate = $autoRecreate;

        return $this;
    }

    /**
     * Get autoRecreate
     *
     * @return boolean
     */
    public function getAutoRecreate()
    {
        return $this->autoRecreate;
    }

    /**
     * Set calculationResult
     *
     * @param array $calculationResult
     * @return BonusCalculateShedule
     */
    public function setCalculationResult($calculationResult)
    {
        $this->calculationResult = $calculationResult;

        return $this;
    }

    /**
     * Get calculationResult
     *
     * @return array
     */
    public function getCalculationResult()
    {
        return $this->calculationResult;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     * @return BonusCalculateShedule
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean 
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }
}
