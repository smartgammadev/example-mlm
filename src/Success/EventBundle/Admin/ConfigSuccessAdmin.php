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
class ConfigSuccessAdmin extends Admin {
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('content')
            //->add('save', 'submit', array('label' => 'Save', 'attr' => array('class' => 'btn btn-primary')));

        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('content')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('content')
        ;
    }
}
