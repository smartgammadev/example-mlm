<?php

namespace Success\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller {   
    
    /**
    * 
    *@Route("/", name="index")
    */
    public function indexAction()
    {
        return $this->render("SuccessSiteBundle:Default:index.html.twig");
    }
    
    /**
    * 
    *@Route("/empty", name="empty")
    */
    public function emptyAction()
    {
        return $this->render("SuccessSiteBundle::empty.html.twig");
    }
}
