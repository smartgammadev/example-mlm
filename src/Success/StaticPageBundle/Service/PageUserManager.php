<?php

namespace Success\StaticPageBundle\Service;

use Doctrine\ORM\Mapping as ORM;
use Success\PlaceholderBundle\Entity\BasePlaceholder;
use Success\MemberBundle\Entity\Member;


class PageUserManager {
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    use \Success\MemberBundle\Traits\SetMemberManagerTrait;
    use \Success\PricingBundle\Traits\ProductPricingManagerTrait;
    
    public function getAccessByUser($pageId,Member $member)
    {
        $pricing = $this->productPricingManager->getCurrentForMember($member);

        if ($pricing) {
            $repo = $this->em->createQueryBuilder()
                    ->select('p')
                    ->from('SuccessStaticPageBundle:PageProductPricing', 'p')
                    ->where('p.page = :pageid')
                    ->andWhere('p.productPricing = :productPricing')
                    ->setParameter('pageid', $pageId)
                    ->setParameter('productPricing', $pricing->getProductPricing()->getId())
                    ->getQuery()
                    ->getResult();
        }
        
        if ((isset($repo)) and !empty($repo)) {
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
    
    public function getUserData (Member $member) {
        $placeholders = $this->em->getRepository('SuccessPlaceholderBundle:BasePlaceholder')->findAll();

        foreach ($placeholders as $placeholder) {
            $userOrSponsor = explode("_",$placeholder->getFullPattern());

            if ($userOrSponsor[0] == 'user') {
                $memberData = $this->memberManager->getMemberData($member, $placeholder);
            }
            if ($userOrSponsor[0] == 'sponsor') {
                $memberData = $this->memberManager->getMemberData($member->getSponsor(), $placeholder);
            }
                $userData[$placeholder->getFullPattern()] = $memberData;
        }
        
        return $userData;
    }
}
