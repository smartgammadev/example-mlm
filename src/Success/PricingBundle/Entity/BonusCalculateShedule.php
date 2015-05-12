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
     * @var \DateTime
     *
     * @ORM\Column(name="calculation_date_from", type="datetime")
     */
    private $calculationDateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="calculation_date_to", type="datetime")
     */
    private $calculationDateTo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_processed", type="boolean")
     */
    private $isProcessed;

    /**
     * @var \JMS\JobQueueBundle\Entity\Job
     * @ORM\OneToOne(targetEntity="JMS\JobQueueBundle\Entity\Job")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     **/
    private $job;
    
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
     * Set calculationDateFrom
     *
     * @param \DateTime $calculationDateFrom
     * @return BonusCalculateShedule
     */
    public function setCalculationDateFrom($calculationDateFrom)
    {
        $this->calculationDateFrom = $calculationDateFrom;

        return $this;
    }

    /**
     * Get calculationDateFrom
     *
     * @return \DateTime
     */
    public function getCalculationDateFrom()
    {
        return $this->calculationDateFrom;
    }

    /**
     * Set calculationDateTo
     *
     * @param \DateTime $calculationDateTo
     * @return BonusCalculateShedule
     */
    public function setCalculationDateTo($calculationDateTo)
    {
        $this->calculationDateTo = $calculationDateTo;

        return $this;
    }

    /**
     * Get calculationDateTo
     *
     * @return \DateTime
     */
    public function getCalculationDateTo()
    {
        return $this->calculationDateTo;
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
}
