<?php

namespace Success\MemberBundle\Twig;

use Success\MemberBundle\Entity\Member;

class SuccessMemberExtension extends \Twig_Extension
{
    use \Success\MemberBundle\Traits\SetMemberLoginManagerTrait;
    use \Success\MemberBundle\Traits\SetMemberManagerTrait;
    use \Success\PricingBundle\Traits\ProductPricingManagerTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'loginSecret' => new \Twig_Function_Method($this, 'getLoginSecret'),
            'referalsCount' => new \Twig_Function_Method($this, 'getMembersReferalsCount'),
            'referalsHasProductCount' => new \Twig_Function_Method($this, 'getMembersReferalsCountHasProduct'),
            'memberName' => new \Twig_Function_Method($this, 'getMemberName'),
            'memberOwnedProduct' => new \Twig_Function_Method($this, 'getMemberPricingProductName'),
        );
    }

    public function getLoginSecret($externalId)
    {
        return $this->memberLoginManager->getRemoteLoginSecret($externalId);
    }

    public function getMembersReferalsCount(Member $member)
    {
        return $this->memberManager->getMemberReferalCount($member);
    }
    
    public function getMembersReferalsCountHasProduct(Member $member, $level = null)
    {
        return $this->memberManager->getMemberReferalsHasProductCount($member, $level);
    }
    
    public function getMemberPricingProductName(Member $member)
    {
        $memberProductPricing = $this->productPricingManager->getCurrentForMember($member);
        if ($memberProductPricing) {
            return $memberProductPricing;
        }
        return null;
    }

    public function getMemberName($member)
    {
        if ($member instanceof Member) {
            return $this->memberManager->getMemberName($member);
        }
        return '';
    }

    public function getName()
    {
        return 'success_member_extension';
    }
}
