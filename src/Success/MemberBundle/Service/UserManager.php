<?php

namespace Success\MemberBundle\Service;

use Success\MemberBundle\Entity\Member;
use Success\PlaceholderBundle\Service\PlaceholderManager;
use Success\PlaceholderBundle\Entity\BasePlaceholder;
use Success\MemberBundle\Entity\MemberData;
use Application\Sonata\UserBundle\Entity\User;

class UserManager
{

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;
    use \Success\MemberBundle\Traits\SetPlaceholderManagerTrait;

    private $userIdentityPlaceholder;

    public function __construct()
    {
        $this->userIdentityPlaceholder = 'email';
    }

    /**
     * @param type $externalId string(255)
     * @return Success\MemberBundle\Entity\Member
     */
    public function getUserByExternalId($externalId)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:Member');
        return $repo->findOneBy(array('externalId' => $externalId));
    }

    /**
     * @param sting $externalId
     * @return Success\MemberBundle\Entity\Member
     */
    public function resolveMemberByExternalId($externalId)
    {
        $member = $this->getMemberByExternalId($externalId);

        if (!$member) {
            $member = new Member();
            $member->setExternalId($externalId);
            $this->em->persist($member);
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
            if ($phData['placeholder']->getPattern() == $this->memberIdentityPlaceholder) {
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
    public function resolveUpdateOrCreateMember(Member $member, BasePlaceholder $placeholder, $data)
    {
        $repo = $this->em->getRepository('SuccessMemberBundle:MemberData');
        $memberData = $repo->findOneBy(array('member' => $member->getId(), 'placeholder' => $placeholder->getId()));

        if (!$memberData) {
            $memberData = new MemberData();
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
            $this->em->persist($memberData);
            $this->em->flush();
        } else {
            $memberData->setMember($member);
            $memberData->setPlaceholder($placeholder);
            $memberData->setMemberData($data);
            $this->em->flush();
        }
        return $memberData;
    }

    /**
     * @Route("/login-token/{token}/{userHash}", name="participant_autologin_token")
     * @ParamConverter("autologinToken", class="MyBundle:AutologinToken")
     * @Template()
     */
    public function autoLoginAction(Request $request, AutologinToken $autologinToken, $userHash)
    {
        $user = $autologinToken->getUser();
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.authentication", $event);
        return $this->redirect($autologinToken->getUrl());
    }
}
