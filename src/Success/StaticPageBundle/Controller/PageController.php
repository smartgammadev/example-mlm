<?php

namespace Success\StaticPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Success\MemberBundle\Entity\Member;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;


class PageController extends Controller {
     /**
     * @var \Success\StaticPageBundle\Service\PageUserManager
     * @DI\Inject("success.staticpage.page_user_meneger")
     */
    private $pageUserManager;
    
     /**
     * @var \Success\MemberBundle\Service\MemberManager
     * @DI\Inject("success.member.member_manager")
     */
    private $memberManager;
       
     /**
     * @Route("/page/{slug}", name="static_page")
     * @Template()
     */
    public function pageAction($slug)
    {              
        $page = $this->getDoctrine()
                ->getRepository('SuccessStaticPageBundle:Page')
                ->findOneBy(array('slug' => $slug, 'isActive' =>TRUE));
                
        if ($page) {
            $member = $this->getUser();  
            if (!$member instanceof Member) {
                throw new AccessDeniedHttpException();
            }
            $access = $this->pageUserManager->getAccessByUser($page->getId(), $member);            
        } else {
            throw new NotFoundHttpException();
        }
        $twig = clone $this->get('twig');
        $twig->setLoader(new \Twig_Loader_String()); 
        
        if ($access) {
            $template = '{% block content %}'.$page->getContent().'{% endblock content %}';
        } else {
            $template = '{% block content %}<h1>У вас нет доступа к странице!</h1>{% endblock content %}';
        }
        
        $userData = $this->pageUserManager->getUserData($member);
        
        $rendered = $twig->render($template, $userData);

        return new Response($rendered);       
    }
}
