<?php
namespace Success\PricingBundle\Utils;

class DateRange
{
    private $dateFrom;
    private $dateTo;
    
    public function __construct(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $dateFrom->setTime(0, 0, 1);
        $dateTo->setTime(23, 59, 59);
        
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function getDateFrom()
    {
        return $this->dateFrom;
    }
    
    public function getDateTo()
    {
        return $this->dateTo;
    }
}
