<?php

namespace Success\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SuccessMemberBundle:Default:index.html.twig', array('name' => $name));
    }
}
