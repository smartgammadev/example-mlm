<?php
namespace Success\MemberBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Success\MemberBundle\Entity\Member;

class MemberProvider implements UserProviderInterface
{
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    public function loadUserByUsername($externalId)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:Member');
        $member = $repo->findOneBy(['externalId' => $externalId]);
        
        if ($member) {
            return $member;
        }
        
        throw new UsernameNotFoundException(sprintf('Member with external id "%s" does not exist.', $externalId));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Member) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Success\MemberBundle\Entity\Member';
    }
}
