<?php

namespace Success\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller {
   
    public function indexAction(){
        return $this->render("SuccessSiteBundle:Default:index.html.twig");
    }
}
