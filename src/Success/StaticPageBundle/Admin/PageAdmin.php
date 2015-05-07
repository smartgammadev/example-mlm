<?php

namespace Success\StaticPageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PageAdmin extends Admin{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('slug', 'text', [])
            ->add('content', 'text', [])
            ->add('isActive', null, array('required' => false))
            ->add('productPricings', 'sonata_type_collection', array('by_reference' => true,'cascade_validation' => false, 'type_options' => array('delete' => true)), array(
                        'allow_delete'=>FALSE,
                        'edit' => 'inline',
                        'sortable' => 'position',
                        'inline' => 'table'
                        ));
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('slug')
//                ->add('content')
                ->add('isActive')
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
                ->add('slug');
    }    
}
