<?php

namespace Success\MemberBundle\Traits;

use Success\MemberBundle\Service\MemberLoginManager;

trait SetMemberLoginManagerTrait
{

    /**
     *
     * @var \Success\MemberBundle\Service\MemberLoginManager $memberLoginManager
     */
    private $memberLoginManager;

    public function setMemberLoginManager(MemberLoginManager $memberLoginManager)
    {
        $this->memberLoginManager = $memberLoginManager;
    }
}
