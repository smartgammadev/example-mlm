<?php

namespace Success\StaticPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;

class PageController extends Controller {
     /**
     * @var \Success\StaticPageBundle\Service\PageUserManager
     * @DI\Inject("success.staticpage.page_user_meneger")
     */
    private $pageUserManager;
    
   
    
     /**
     * @Route("/page/{slug}", name="static_page")
     * @Template()
     */
    public function pageAction($slug)
    {
        $page = $this->getDoctrine()
                ->getRepository('SuccessStaticPageBundle:Page')
                ->findOneBy(array('slug' => $slug, 'isActive' =>TRUE));  

        $member = $this->getUser()->getId();
        if ($page) {
            $access = $this->pageUserManager->getAccessByUser($page->getId(), $member);
        } else {
            $access =FALSE;
        }

        return ['page' => $page];
    }
}
