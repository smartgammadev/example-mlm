<?php

namespace Success\TreasureBundle\Twig;

use Success\MemberBundle\Entity\Member;

class SuccessAccountExtension extends \Twig_Extension
{
    use \Success\TreasureBundle\Traits\SetAccountManagerTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'accountBalance' => new \Twig_Function_Method($this, 'getMemberAccountBalance'),
        );
    }

    public function getMemberAccountBalance(Member $member)
    {
        $accountBalance = $this->accountManager->getOverallAccountBalance($member);
        if ($accountBalance) {
            return $accountBalance->getAmount();
        }
        return 0;
    }

    public function getName()
    {
        return 'success_account_extension';
    }
}
