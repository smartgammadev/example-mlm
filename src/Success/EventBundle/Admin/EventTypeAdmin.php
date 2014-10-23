<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of EventTypeAdmin
 *
 * @author develop1
 */
class EventTypeAdmin extends Admin {
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Event Name'))
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
            ->add('name')
        ;
    }
}
