<?php

namespace Success\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Success\MemberBundle\Entity\Member;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MemberController extends Controller
{

    /**
     * @var \Success\MemberBundle\Service\MemberManager
     * @DI\Inject("success.member.member_manager")
     */
    private $memberManager;

    /**
     * @var \Success\MemberBundle\Service\MemberLoginManager $memberLoginManager
     * @DI\Inject("success.member.login_manager")
     */
    private $memberLoginManager;
    
    /**
     * @var \Success\TreasureBundle\Service\AccountMananger $accountManager
     * @DI\Inject("success.treasure.account_manager")
     */
    private $accountManager;

    /**
     * @Route("/login", name="member_login")
     * @Method({"POST"})
     * @Template()
     */
    public function doLoginAction(Request $request)
    {
        $response = new Response();
        $externalId = $request->request->get('id');
        $secret = $request->request->get('secret');

        if (!$request->isXmlHttpRequest()) {
            $response->setStatusCode(403);
            return $response;
        }
        try {
            $member = $this->memberManager->getMemberByExternalId($externalId);
        } catch (NotFoundHttpException $ex) {
            $response->setStatusCode(403);
            $member = $this->memberLoginManager->doLogout();
            return $response;
        }

        if ($this->memberLoginManager->doLoginMember($member, $secret)) {
            $response->setStatusCode(200);
            return $response;
        } else {
            $response->setStatusCode(403);
            $member = $this->memberLoginManager->doLogout();
            return $response;
        }
    }

    /**
     * @Route("/profile", name="member_profile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $member = $this->memberLoginManager->getLoggedInMember();
        if ($member instanceof Member) {
            $memberBalance = $this->accountManager->getOverallAccountBalance($member);
            $memberReferals = $this->memberManager->getMemberReferals($member);
            
            return
                [
                    'member' => $member,
                    'referals' => $memberReferals,
                    'balance' => ($memberBalance == null ? 0 : $memberBalance->getAmount()),
                ];
        }
        throw new AccessDeniedHttpException();
    }
}
