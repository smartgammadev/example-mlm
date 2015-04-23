<?php

namespace Success\MemberBundle\Traits;

use Success\MemberBundle\Service\MemberManager;

trait SetMemberManagerTrait
{

    /**
     *
     * @var \Success\MemberBundle\Service\MemberManager $memberManager
     */
    private $memberManager;

    public function setMemberManager(MemberManager $memberManager)
    {
        $this->memberManager = $memberManager;
    }
}
