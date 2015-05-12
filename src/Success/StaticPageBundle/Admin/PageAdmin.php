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
                ->add('content', 'ckeditor', array(
                    'config' => array(),
                ));
        
        if ($this->id($this->getSubject())){
        $formMapper
            ->add('productPricings', 'sonata_type_collection',
                    array(
                        'by_reference' => false,
                        'cascade_validation' => true, 
                        'type_options' => array('delete' => true)
                    ), 
                    array(
                        'allow_delete'=>FALSE,
                        'edit' => 'inline',
                        'sortable' => 'position',
                        'inline' => 'table'
                    ));
        }
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('slug')
//                ->add('content')
                ->add('Page Link', null, ['template' =>'SuccessStaticPageBundle:Sonata:Fields/page_link.html.twig'])
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
