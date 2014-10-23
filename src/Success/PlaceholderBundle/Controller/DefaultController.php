<?php

namespace Success\PlaceholderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SuccessPlaceholderBundle:Default:index.html.twig', array('name' => $name));
    }
}
