<?php

namespace Success\MemberBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\PlaceholderBundle\Service\PlaceholderManager;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Success\PlaceholderBundle\Entity\BasePlaceholder;
use Success\MemberBundle\Entity\MemberData;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MemberManager
{
    const MEMBER_IDENTITY_PLACEHOLDER = 'email';
    
    const MEMBER_FIRSTNAME_PLACEHOLDER = 'first_name';
    const MEMBER_LASTNAME_PLACEHOLDER = 'last_name';
    
    const SPONSOR_PLACEHOLDER_TYPE_NAME = 'sponsor';
    const USER_PLACEHOLDER_TYPE_NAME = 'user';
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    use \Success\MemberBundle\Traits\SetPlaceholderManagerTrait;

    /**
     * @param type $externalId string(255)
     * @return Success\MemberBundle\Entity\Member
     */
    public function getMemberByExternalId($externalId)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:Member');
        $member = $repo->findOneBy(['externalId' => $externalId]);
        if (!$member) {
            throw new NotFoundHttpException();
        }
        return $member;
    }
    
    /**
     * @param sting $externalId
     * @return Success\MemberBundle\Entity\Member
     */
    public function resolveMemberByExternalId($externalId, $sponsorExternalId = null)
    {
        try {
            $member = $this->getMemberByExternalId($externalId);
        } catch (NotFoundHttpException $ex) {
            $member = new Member();
            $sponsor = $this->getMemberByExternalId($sponsorExternalId);
            $member->setExternalId($externalId);
            $member->setSponsor($sponsor);            
            $this->em->persist($member);
            $sponsor->addReferal($sponsor);
            $this->em->flush();
        }
        return $member;
    }

    /**
     * @param array $placeholders
     * @return bool
     */
    public function updateMemberData(array $placeholders)
    {
        $placeholdersData = $this->placeholderManager->getPlaceholdersValuesFormSession($placeholders);

        $placeholdersToSearchMember = $this->findPlaceholdersToSearchMember($placeholdersData);
        $this->findMemberAndResolveUpdateOrCreate($placeholdersToSearchMember);
    }

    /**
     * @param array $placeholdersData
     * @return array
     */
    private function findPlaceholdersToSearchMember(array $placeholdersData)
    {
        $placeholdersToSearchMember = array();

        foreach ($placeholdersData as $phData) {
            if ($phData['placeholder']->getPattern() == self::MEMBER_IDENTITY_PLACEHOLDER) {
                $placeholdersToSearchMember[] = array('externalId' => $phData['value'],
                    'pattern' => $phData['placeholder']->getPlaceholderType()->getPattern());
            }
        }
        return $placeholdersToSearchMember;
    }

    /**
     * @param array $placeholdersToSearchMember
     * @return void
     */
    private function findMemberAndResolveUpdateOrCreate(array $placeholdersToSearchMember)
    {
        foreach ($placeholdersToSearchMember as $searchPlaceholder) {
            $member = $this->resolveMemberByExternalId($searchPlaceholder['externalId']);
            $placeholdersMemberData = $this->placeholderManager->getPlaceholdersValuesByTypePattern($searchPlaceholder['pattern']);

            foreach ($placeholdersMemberData as $phData) {
                $this->resolveUpdateOrCreateMember($member, $phData['placeholder'], $phData['value']);
            }
        }
    }

    /**
     * @param \Success\MemberBundle\Entity\Member $member
     * @param \Success\PlaceholderBundle\Entity\BasePlaceholder $placeholder
     * @param string $data
     * @return \Success\MemberBundle\Entity\MemberData
     */
    private function resolveMemberData(Member $member, BasePlaceholder $placeholder, $data)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:MemberData');
        $memberData = $repo->findOneBy(array('member' => $member->getId(), 'placeholder' => $placeholder->getId()));

        if (!$memberData) {
            $memberData = new MemberData();
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
            $this->em->persist($memberData);
        } else {
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
        }
        return $memberData;
    }
    
    /**
     * @return Member
     * @throws NotFoundHttpException
     */
    private function getSponsorMemberFromPlaceholders()
    {
        $placeholdersData = $this->placeholderManager->getPlaceholdersValuesFormSession();
        foreach ($placeholdersData as $placeholderData) {
            if ($placeholderData['placeholder']->getFullPattern() ==
                    self::SPONSOR_PLACEHOLDER_TYPE_NAME.'_'.self::MEMBER_IDENTITY_PLACEHOLDER) {
                $sponsorExternalId = $placeholderData['value'];
            }
        }
        if (!isset($sponsorExternalId)) {
            throw new NotFoundHttpException('Sponsor identity not found in placeholders. Link is not valid.');
        }
        try {
            $sponsorMember = $this->getMemberByExternalId($sponsorExternalId);
        } catch (NotFoundHttpException $ex) {
            throw new NotFoundHttpException('Sponsor provided in placeholders not found. Link is not valid.');
        }
        return $sponsorMember;
    }
    
    /**
     * @return Member
     * @throws NotFoundHttpException
     */
    public function resolveUserMemberFromPlaceholders()
    {
        $placeholdersData = $this->placeholderManager->getPlaceholdersValuesFormSession();
        foreach ($placeholdersData as $placeholderData) {
            if ($placeholderData['placeholder']->getFullPattern() ==
                    self::USER_PLACEHOLDER_TYPE_NAME.'_'.self::MEMBER_IDENTITY_PLACEHOLDER) {
                $userExternalId = $placeholderData['value'];
            }
        }
        if (!isset($userExternalId)) {
            throw new NotFoundHttpException('User identity not found in placeholders. Link is not valid.');
        }
        try {
            $userMember = $this->getMemberByExternalId($userExternalId);
        } catch (NotFoundHttpException $ex) {
            $userMember = $this->createIncomingMember($userExternalId, $this->getSponsorMemberFromPlaceholders());
        }
        return $userMember;
    }

    /**
     * @param String $externalId
     * @param Member $sponsorMember
     * @return Member
     */
    private function createIncomingMember($externalId, Member $sponsorMember)
    {
        $member = new Member();
        $member->setExternalId($externalId);
        $member->setSponsor($sponsorMember);
        $this->em->persist($member);
        $this->updateMemberDataFromPlaceholders(self::USER_PLACEHOLDER_TYPE_NAME, $member);
        
        $this->em->flush();
        return $member;
    }
    
    /**
     * @param type $placeholderTypeName
     * @param Member $member
     */
    private function updateMemberDataFromPlaceholders($placeholderTypeName, Member $member)
    {
        $placeholdersData = $this->placeholderManager->getPlaceholdersValuesFormSession();
        foreach ($placeholdersData as $placeholderData) {
            if ($placeholderData['placeholder']->getPlaceholderType()->getPattern() == $placeholderTypeName) {
                $this->resolveMemberData($member, $placeholderData['placeholder'], $placeholderData['value']);
            }
        }
    }
    
    /**
     * @param Member $member
     * @return string
     */
    public function getMemberRepresentiveName(Member $member)
    {
        
    }
    
    /**
     * @param Member $sponsor
     * @return integer
     */
    public function getMemberReferalCount(Member $sponsor)
    {
        /**
         * @var $memberRepo \Gedmo\Tree\Entity\Repository\NestedTreeRepository
         */
        $memberRepo = $this->em->getRepository('SuccessMemberBundle:Member');
        $childCount = $memberRepo->childCount($sponsor);
        return $childCount;
    }
}
