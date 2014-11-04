<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebinarSignUps
 *
 * @author develop1
 */
class WebinarSignUpAdmin extends Admin
{       
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'signUpDateTime' // name of the ordered field (default = the model id field, if any)
    );
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('signUpDateTime')
            ->add('event')
            ->add('member')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('signUpDateTime','datetime',array('format' => 'd.m.Y H:i'))
            ->add('event')
            ->add('member')
            ->add('_action', 'actions', array(
                    'actions' => array(
                    'delete'      => array(),    
                )
            ))
        ;
    }
    
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('edit');
    }
    
}
