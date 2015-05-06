<?php
namespace Success\PricingBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\PricingBundle\Entity\ProductPricing;
use Success\PricingBundle\Entity\ProductPricingMember;
use Success\TreasureBundle\Exception\NotEnoughAmountException;

class ProductPricingManager
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    use \Success\TreasureBundle\Traits\SetAccountManagerTrait;
    use \Success\MemberBundle\Traits\SetMemberManagerTrait;

    /**
     * @param ProductPricing $productPricing
     * @param Member $member
     * @return ProductPricingMember
     */
    private function assignToMember(ProductPricing $productPricing, Member $member)
    {
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
    
    /**
     * @param ProductPricing $productPricing
     * @param Member $member
     * @return ProductPricingMember
     */
    public function processForMember(ProductPricing $productPricing, Member $member)
    {
        if ($this->checkIfCanBeAssignedToMember($productPricing, $member)) {
            $this->accountManager->doAccountOperation($member, -1 * $productPricing->getProductPrice(), "product bying {$productPricing->getProductName()}");
            
            return $this->assignToMember($productPricing, $member);
        }
    }
    
    /**
     * @param ProductPricing $productPricing
     * @param Member $member
     * @return boolean
     * @throws NotEnoughAmountException
     */
    public function checkIfCanBeAssignedToMember(ProductPricing $productPricing, Member $member)
    {
        /* @var $accountBalance \Success\TreasureBundle\Entity\AccountBalance */
        $accountBalance = $this->accountManager->getOverallAccountBalance($member);
        if (!$accountBalance || $accountBalance->getAmount() < $productPricing->getProductPrice()) {
            throw new NotEnoughAmountException('Not enough amount on balance');
        }
        return true;
    }

    /**
     * @param string $name
     * @return ProductPricing
     */
    public function getActiveByName($name)
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:ProductPricing');
        return $repo->findOneBy(['isActive' => true, 'productName' => $name]);
    }

    
    /**
     * @param integer $id
     * @return ProductPricing
     */
    public function getActiveById($id)
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:ProductPricing');
        return $repo->findOneBy(['isActive' => true, 'id' => $id]);
    }
    
    /**
     * @return Array
     */
    public function getActivePricings()
    {
        $repo = $this->em->getRepository('SuccessPricingBundle:ProductPricing');
        return $repo->findBy(['isActive' => true]);
    }
    
    /**
     * @param Member $member
     * @return ProductPricingMember
     */
    public function getCurrentForMember(Member $member)
    {
        $productPricingRepo = $this->em->getRepository('SuccessPricingBundle:ProductPricingMember');
        return $productPricingRepo->findLastProductPricingForMember($member);
    }
}
