<?php

namespace Success\MemberBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository as BaseRepository;

class MemberRepository extends BaseRepository
{

    public function childrenHasProduct($node, $direct)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node, $direct);
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        return $qb->getQuery()->getResult();
    }

    public function childrenHasProductCount($node, $level = null)
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
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function childrenHasProductPaidSum($node)
    {
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('sum(pm.pricePaid)');
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
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
