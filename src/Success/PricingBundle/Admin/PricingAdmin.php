<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PricingAdmin extends Admin
{

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', 'text', array('label' => 'Pricing Name'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('name')
        ;
    }
    
    public function getNewInstance()
    {
        //parent::getNewInstance();
    }
}
