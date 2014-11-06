<?php

namespace Success\SalesGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/sales_generator", name="sales_generator")
     * @Template("SuccessSalesGeneratorBundle:Default:sales_generator.html.twig")
     */
    public function salesGeneratorAction()
    {
        return array();
    }
}
