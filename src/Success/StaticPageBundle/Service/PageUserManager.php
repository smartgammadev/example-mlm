<?php

namespace Success\StaticPageBundle\Service;

use Doctrine\ORM\Mapping as ORM;
use Success\PlaceholderBundle\Entity\BasePlaceholder;
use Success\MemberBundle\Entity\Member;
use Success\StaticPageBundle\Entity\Page;
use Success\PricingBundle\Entity\ProductPricing;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageUserManager
{
    const FORBIDDEN_PAGE_SLUG = 'forbidden';

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    use \Success\MemberBundle\Traits\SetMemberManagerTrait;

    use \Success\PricingBundle\Traits\ProductPricingManagerTrait;

    public function getAccessByUser(Page $page, Member $member)
    {
        $pricing = $this->productPricingManager->getCurrentForMember($member);
        $availablePricingsForPage = $page->getProductPricings();
        
        if ($availablePricingsForPage->count() == 0) {
            return true;
        }
        if ($this->checkAccessForPageInPricing($page, $pricing->getProductPricing())) {
            return true;
        }
        return false;
    }
    
    private function checkAccessForPageInPricing(Page $page, ProductPricing $pricing)
    {
        $repo = $this->em->getRepository('SuccessStaticPageBundle:PageProductPricing');
        $access = $repo->findOneBy(['productPricing' => $pricing, 'page' => $page]);
        return $access;
    }
    
    public function getPageBySlug($slug)
    {
        $page = $this->em
                ->getRepository('SuccessStaticPageBundle:Page')
                ->findOneBy(array('slug' => $slug, 'isActive' =>true));
        return $page;
    }
    
    /**
     * Gets a forbidden page, when it not exists calls create method
     * @return Page
     */
    public function getForbiddenPage()
    {
        $page = $this->em
                ->getRepository('SuccessStaticPageBundle:Page')
                ->findOneBy(array('slug' => self::FORBIDDEN_PAGE_SLUG, 'isActive' =>true));
        if ($page === null) {
            $page = $this->createDefaultForbiddenPage();
        }
        return $page;
    }
    
    /**
     * Creates a default forbidden page
     * @return Page
     */
    private function createDefaultForbiddenPage()
    {
        $page = new Page();
        $page->setIsActive(true);
        $page->setSlug(self::FORBIDDEN_PAGE_SLUG);
        $page->setContent('403: access denied');
        $this->em->persist($page);
        $this->em->flush();
        
        return $page;
    }

    public function getUserData(Member $member)
    {
        $placeholders = $this->em->getRepository('SuccessPlaceholderBundle:BasePlaceholder')->findAll();

        foreach ($placeholders as $placeholder) {
            $userOrSponsor = explode("_", $placeholder->getFullPattern());

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
