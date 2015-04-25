<?php
namespace Success\MemberBundle\Service;

use Success\MemberBundle\Entity\Member;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MemberLoginManager
{

    use \Success\MemberBundle\Traits\SetRedisClientTrait;
    use \Success\MemberBundle\Traits\SetSecurityContextTrait;
    
    /**
     * 
     * @param string $externalId
     * @return string
     */
    private function generateRemoteLoginSecret($externalId)
    {
        return $randomStringForEventIdentifier = md5('ihgmst4,cnsfy]wgh'.$externalId.rand().date('m-d-y').rand()).md5($externalId.'hng;27sdmgnan25gs'.rand().date('m-d-y').rand());
    }
    
    /**
     * 
     * @param string $externalId
     * @return string
     */
    public function getRemoteLoginSecret($externalId)
    {
        $token = $this->generateRemoteLoginSecret($externalId);
        $this->redisClient->setex($externalId, 10, $token);
        return $token;
    }
    
    /**
     * @param string $externalId
     * @param string $token
     * @return boolean
     */
    private function checkRemoteLoginSecret($externalId, $secret)
    {
        $key = $this->redisClient->get($externalId);
        return $key == $secret;
    }
    
    public function removeRemoteLoginSecret($externalId)
    {
        $this->redisClient->del($externalId);
    }


    /**
     * @param Member $member
     */
    public function doLoginMember(Member $member, $secret)
    {
        $result = false;
        if ($this->checkRemoteLoginSecret($member->getExternalId(), $secret)) {
            $token = new UsernamePasswordToken(
                $member,
                $member->getPassword(),
                "success.member.member_provider",
                $member->getRoles()
            );
            $this->securityContext->setToken($token);
            $this->removeRemoteLoginSecret($member->getExternalId());
            $result = true;
        }
        return $result;
    }
    
    public function doLogout()
    {
        $token = new AnonymousToken('user', 'anon.');
        $this->securityContext->setToken($token);
    }
    
    public function getLoggedInMember()
    {
        return $this->securityContext->getToken()->getUser();
    }    
}
