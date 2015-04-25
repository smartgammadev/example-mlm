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
}
