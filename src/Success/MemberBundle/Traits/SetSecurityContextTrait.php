<?php

namespace Success\MemberBundle\Traits;

use Symfony\Component\Security\Core\SecurityContext;

trait SetSecurityContextTrait
{
    /**
     *
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
