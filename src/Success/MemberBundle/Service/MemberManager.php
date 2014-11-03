<?php
namespace Success\MemberBundle\Service;
use Success\MemberBundle\Entity\Member;
use JMS\DiExtraBundle\Annotation\Inject;
use Success\PlaceholderBundle\Service\PlaceholderManager;
use Success\PlaceholderBundle\Entity\BasePlaceholder;
use Success\MemberBundle\Entity\MemberData;
class MemberManager {
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    
    private $placeholderManager;
    
    public function __construct(PlaceholderManager $placeholderManager)
    {
        $this->placeholderManager = $placeholderManager;
    }
    
    
    /**
     * 
     * @param type $externalId string(255)
     * @return Success\MemberBundle\Entity\Member
     */
    public function GetMemberByExternalId($externalId)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:Member');
        return $repo->findOneBy(array('externalId'=>$externalId));
    }
    
    /**
     * 
     * @param type $externalId sting(255)
     * @return Success\MemberBundle\Entity\Member
     */
    public function ResolveMemberByExternalId($externalId)
    {
        $member = $this->GetMemberByExternalId($externalId);
        
        $persisted = false;
        if (!$member){
            $member = new Member();
            $member->setExternalId($externalId);
            $this->em->persist($member);
            $persisted = true;
        }
        if ($persisted){
            $this->em->flush();
        }
        return $member;
    }
    
    public function UpdateMemberData($placeholders,$memberIdentityPlaceholder)
    {   
        $placeholdersData = $this->placeholderManager->getPlaceholdersValuesFormSession($placeholders);
                        
        foreach ($placeholdersData as $phData){
            if ($phData['placeholder']->getPattern()==$memberIdentityPlaceholder){
                $membersFound[] = array('externalId'=>$phData['value'], 
                    'pattern'=>$phData['placeholder']->getPlaceholderType()->getPattern());
            }
        }
        
        foreach ($membersFound as $memberFound){
            $member = $this->ResolveMemberByExternalId($memberFound['externalId']);
            $placeholdersMemberData = 
                    $this->placeholderManager->getPlaceholdersValuesByTypePattern($memberFound['pattern']);
            
            foreach ($placeholdersMemberData as $phData){
                $this->ResolveMemberData($member, $phData['placeholder'], $phData['value']);
            }
        }        
    }
    
    /**
     * 
     * @param Member $member
     * @param BasePlaceholder $placeholder
     * @param type $data String
     * @return void
     */
    public function ResolveMemberData($member, $placeholder, $data)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:MemberData');
        $memberData = $repo->findOneBy(array('member'=>$member->getId(), 'placeholder'=>$placeholder->getId()));
        
        if (!$memberData){
            $memberData = new MemberData();
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
            $this->em->persist($memberData);
            $this->em->flush();
            return $memberData;
        } else {
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
            $this->em->flush();
            return $memberData;
        }
        
        
    }
    
}
