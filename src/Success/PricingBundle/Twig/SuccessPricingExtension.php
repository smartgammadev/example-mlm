<?php

namespace Success\PricingBundle\Twig;

class SuccessPricingExtension extends \Twig_Extension
{

    use \Success\PricingBundle\Traits\BonusCalculatorTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'bonusAmount' => new \Twig_Function_Method($this, 'getBonusAmount'),
        );
    }

    public function getBonusAmount($bonusCalculation)
    {
        return $this->bonusCalculator->getBonusAmountByCalculation($bonusCalculation);
    }

    public function getName()
    {
        return 'success_pricing_extension';
    }
}
