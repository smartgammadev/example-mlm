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

class PageController extends Controller
{

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
        $twig = clone $this->get('twig');
        $twig->setLoader(new \Twig_Loader_String());
        
        $page = $this->pageUserManager->getPageBySlug($slug);
        if ($page) {
            $member = $this->getUser();
            if (!$member instanceof Member) {
                $template = '{% block content %}' . $this->pageUserManager->getForbiddenPage()->getContent() . '{% endblock content %}';
                $rendered = $twig->render($template);
                return new Response($rendered);
            }
            $access = $this->pageUserManager->getAccessByUser($page, $member);
        } else {
            throw new NotFoundHttpException();
        }
        if ($access) {
            $template = '{% block content %}' . $page->getContent() . '{% endblock content %}';
        } else {
            $template = '{% block content %}' . $this->pageUserManager->getForbiddenPage()->getContent() . '{% endblock content %}';
        }
        $userData = $this->pageUserManager->getUserData($member);
        $rendered = $twig->render($template, $userData);
        return new Response($rendered);
    }
}
