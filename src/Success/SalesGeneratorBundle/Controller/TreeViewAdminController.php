<?php

namespace Success\SalesGeneratorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Controller\CRUDController;
use JMS\DiExtraBundle\Annotation as DI;

class TreeViewAdminController extends CRUDController
{
    /**
     * @var \Success\SalesGeneratorBundle\Service\SalesGeneratorManager
     * @DI\Inject("success.sales_generator.manager")
     */
    private $salesGeneratorManager;
    
    
    public function listAction()
    {        
        $template = $this->admin->getTemplate('list');
        
        $audiences = $this->salesGeneratorManager->getAllAudiences();
        var_dump($audiences); die;
              
        return $this->render($template, ['audiences' => $audiences]);
    }
}
