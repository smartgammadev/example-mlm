<?php
namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductPricingAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('productName', 'text', [])
            ->add('productPrice', 'number', [])
            ->add('isActive')
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('productName')
                ->add('created')
                ->add('productPrice')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('productName');
    }
}
