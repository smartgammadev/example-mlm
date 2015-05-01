<?php

namespace Success\TreasureBundle\Traits;

use Success\TreasureBundle\Service\AccountManager;

trait SetAccountManagerTrait
{
    /* @var $accountManager \Success\TreasureBundle\Service\AccountManager */
    private $accountManager;

    public function setAccountManager(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
    }
}
