<?php

namespace Success\MemberBundle\Twig;


class SuccessMemberExtension extends \Twig_Extension
{
    use \Success\MemberBundle\Traits\SetMemberLoginManagerTrait;
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'loginSecret' => new \Twig_Function_Method($this, 'getLoginSecret'),
        );
    }
    
    public function getLoginSecret($externalId)
    {
        return $this->memberLoginManager->getRemoteLoginSecret($externalId);
    }
    
    public function getName()
    {
        return 'success_member_extension';
    }

}
