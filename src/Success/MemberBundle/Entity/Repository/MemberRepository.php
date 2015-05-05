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

    public function childrenHasProductCount($node)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('count(pm.member)');
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        return (int)$qb->getQuery()->getSingleScalarResult();
    }
    
    public function childrenHasProductPaidSum($node)
    {
        $qb = $this->childrenQueryBuilder($node);
        $qb->select('sum(pm.pricePaid)');
        $qb->join('SuccessPricingBundle:ProductPricingMember', 'pm', 'node = pm.member');
        $qb->andWhere('node.id = pm.member');
        return (float)$qb->getQuery()->getSingleScalarResult();        
    }
}
