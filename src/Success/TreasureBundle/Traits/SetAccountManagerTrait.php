<?php

namespace Success\TreasureBundle\Traits;

use Success\TreasureBundle\Service\AccountMananger;

trait SetAccountManagerTrait
{
    /* @var $accountMananger \Success\TreasureBundle\Service\AccountMananger */
    private $accountMananger;

    public function setAccountManager(AccountMananger $accountMananger)
    {
        $this->accountMananger = $accountMananger;
    }
}
