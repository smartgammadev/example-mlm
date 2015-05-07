<?php

namespace Success\StaticPageBundle\Service;

use Doctrine\ORM\Mapping as ORM;

class PageUserManager {
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function getAccessByUser($pageId, $member)
    {
        $pricingId = $this->em->getRepository('SuccessPricingBundle:ProductPricingMember')
                ->findOneBy(['member' => $member])->getProductPricing()->getId();
        $repo = $this->em->createQueryBuilder()
                ->select('p')
                ->from('SuccessStaticPageBundle:PageProductPricing', 'p')
                ->where('p.page = :pageid')
                ->andWhere('p.productPricing = :productPricing')
                ->setParameter('pageid', $pageId)
                ->setParameter('productPricing', $pricingId)
                ->getQuery()
                ->getResult();
        if ($repo) {
            $access = TRUE; 
        } else {           
            $notPricing = $this->em->createQueryBuilder()
                ->select('p')
                ->from('SuccessStaticPageBundle:PageProductPricing', 'p')
                ->where('p.page = :pageid')
                ->setParameter('pageid', $pageId)
                ->getQuery()
                ->getResult();
            if ($notPricing) {
                $access = FALSE;
            } else {
                $access = TRUE;
            }
        }
        return $access;

    }
}
