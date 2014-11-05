<?php

namespace Success\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BaseEventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BaseEventRepository extends EntityRepository
{
    public function findAllBetweenDates($startDate,$endDate) {
        return  $this->getEntityManager()->createQuery(
                "select e from SuccessEventBundle:BaseEvent e where e.startDateTime BETWEEN :start_date AND :end_date ORDER BY e.startDateTime")
                ->setParameter("start_date", $startDate->format('Y-m-d H:i:s'))
                ->setParameter("end_date", $endDate->format('Y-m-d H:i:s'))
                ->getResult();
    }
    
    public function findNextNearestByDate(\DateTime $startDate)
    {
        $dql = 'select e from SuccessEventBundle:BaseEvent e where e.startDateTime > :start_date ORDER BY e.startDateTime DESC';
        $result = $this->getEntityManager()->createQuery($dql)
                    ->setParameter('start_date', $startDate->format('Y-m-d H:i:s'))
                    ->setFirstResult(0)
                    ->setMaxResults(1)
                    ->getResult();        
        return $result[0];
    }        
}
