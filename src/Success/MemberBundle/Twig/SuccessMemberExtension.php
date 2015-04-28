<?php

namespace Success\MemberBundle\Twig;

use Success\MemberBundle\Entity\Member;

class SuccessMemberExtension extends \Twig_Extension
{

    use \Success\MemberBundle\Traits\SetMemberLoginManagerTrait;
    use \Success\MemberBundle\Traits\SetMemberManagerTrait;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'loginSecret' => new \Twig_Function_Method($this, 'getLoginSecret'),
            'referalsCount' => new \Twig_Function_Method($this, 'getMembersReferalsCount'),
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

    public function getName()
    {
        return 'success_member_extension';
    }
}
