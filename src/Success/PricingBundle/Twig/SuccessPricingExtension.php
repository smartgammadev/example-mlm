<?php

namespace Success\PricingBundle\Twig;

class SuccessMemberExtension extends \Twig_Extension
{
//    use \Success\MemberBundle\Traits\SetMemberLoginManagerTrait;
//    use \Success\MemberBundle\Traits\SetMemberManagerTrait;
//    use \Success\PricingBundle\Traits\ProductPricingManagerTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'bonusAmount' => new \Twig_Function_Method($this, 'getBonusAmout'),
//            'referalsCount' => new \Twig_Function_Method($this, 'getMembersReferalsCount'),
//            'referalsHasProductCount' => new \Twig_Function_Method($this, 'getMembersReferalsCountHasProduct'),
//            'memberName' => new \Twig_Function_Method($this, 'getMemberName'),
//            'memberOwnedProduct' => new \Twig_Function_Method($this, 'getMemberPricingProductName'),
        );
    }
    
    public function getName()
    {
        return 'success_pricing_extension';
    }
}
