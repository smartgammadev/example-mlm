<?php

namespace Success\TreasureBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AccountOperationAdmin extends Admin
{

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('delete');
        $collection->remove('batch');
        $collection->remove('edit');
        $collection->remove('show');
        $collection->remove('export');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('dateOperation')
                ->add('amount')
                ->add('member')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('dateOperation')
                ->add('amount')
                ->add('subAccount')
        ;
    }
}
