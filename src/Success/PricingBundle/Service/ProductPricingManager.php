<?php
namespace Success\PricingBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\PricingBundle\Entity\ProductPricing;
use Success\PricingBundle\Entity\ProductPricingMember;

class ProductPricingManager
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    /**
     * @param ProductPricing $productPricing
     * @param Member $member
     * @return ProductPricingMember
     */
    public function assignProductPricingToMember(ProductPricing $productPricing, Member $member)
    {
        if ($productPricing->getIsActive()) {
            $assignDate = new \DateTime();
            $newProductPricingMember = new ProductPricingMember();
            $newProductPricingMember->setAssignDate($assignDate);
            $newProductPricingMember->setMember($member);
            $newProductPricingMember->setProductPricing($productPricing);
            $newProductPricingMember->setPricePaid($productPricing->getProductPrice());
            $this->em->persist($newProductPricingMember);
            $this->em->flush();

            return $newProductPricingMember;
        }
        return null;
    }
    
    /**
     * @param string $name
     * @return ProductPricing
     */
    public function getActiveProductPricingByName($name)
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:ProductPricing');
        return $repo->findOneBy(['isActive' => true, 'productName' => $name]);
    }
    
    /**
     * @param Member $member
     * @return ProductPricingMember
     */
    public function getCurrentProductPricingForMember(Member $member)
    {
        $productPricingRepo = $this->em->getRepository('SuccessPricingBundle:ProductPricingMember');
        return $productPricingRepo->findLastProductPricingForMember($member);
    }
}
