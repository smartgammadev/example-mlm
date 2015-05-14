<?php

namespace Success\MemberBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository as BaseRepository;
use Success\PricingBundle\Utils\DateRange;
use Doctrine\ORM\QueryBuilder;

class MemberRepository extends BaseRepository
{

    public function childrenHasProduct($node, $isDirect, DateRange $dateRange = null)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node, $isDirect);
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        if ($dateRange) {
            $qb = $this->addDateRangeToQueryBuilder($qb, $dateRange);
        }
        return $qb->getQuery()->getResult();
    }
    
    private function addDateRangeToQueryBuilder(QueryBuilder $qb, DateRange $dateRange)
    {
        $qb->andWhere('pm.assignDate BETWEEN :date_from AND :date_to')
            ->setParameter('date_from', $dateRange->getDateFrom()->format('Y-m-d H:i:s'))
            ->setParameter('date_to', $dateRange->getDateTo()->format('Y-m-d H:i:s'));
        return $qb;
    }

    public function childrenHasProductCount($node, DateRange $dateRange = null, $level = null)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('count(pm.member)');
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        if ($level) {
            $ofLevel = $node->getLvl() + $level;
            $qb->andWhere('node.lvl = :of_level');
            $qb->setParameter('of_level', $ofLevel);
        }
        if ($dateRange) {
            $qb = $this->addDateRangeToQueryBuilder($qb, $dateRange);
        }
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function childrenHasProductPaidSum($node, DateRange $dateRange = null)
    {
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('sum(pm.pricePaid)');
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        if ($dateRange) {
            $qb = $this->addDateRangeToQueryBuilder($qb, $dateRange);
        }
        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    public function childrenOfLevel($node, $level)
    {
        $ofLevel = $node->getLvl() + $level;
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node);
        $qb->andWhere('node.lvl = :of_level');
        $qb->setParameter('of_level', $ofLevel);
        return $qb->getQuery()->getResult();
    }

    public function childrenByLevelSymmary($node)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('count(node.id) as referalsCount, node.lvl as referalsLevel');
        $qb->addGroupBy('node.lvl');
        $qb->addOrderBy('node.lvl');
        return $qb->getQuery()->getResult();
    }
}
