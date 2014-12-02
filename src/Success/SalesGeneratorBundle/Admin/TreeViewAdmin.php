<?php

namespace Success\SalesGeneratorBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Route\RouteCollection;

class TreeViewAdmin extends Admin
{
    protected $baseRouteName = 'tree_view';    
    protected $baseRoutePattern = 'tree_view';
    
    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
        $collection->add('show');
    }
    
    public function getTemplate($name)
    {
        if ('show' == $name) {
            $this->setTemplate('list', 'SuccessSalesGeneratorBundle:Admin:tree_view.html.twig');
        }
        
        return parent::getTemplate($name);
    }
}