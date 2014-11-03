<?php
namespace Success\PlaceholderBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;



class ExternalPlaceholderAdmin extends Admin {
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper       
            ->add('name', 'text', array('label' => 'Placeholder Name'))
            ->add('pattern', 'text', array('label' => 'Placeholder Pattern'))
            ->add('placeholderType', 'sonata_type_model', array('class' => 'Success\PlaceholderBundle\Entity\PlaceholderType'))
            //->add('placeholderType', 'sonata_type_model', array('class' => 'Success\PlaceholderBundle\Entity\PlaceholderType'))
        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')            
            ->add('pattern')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('placeholderType','text',array())
//            ->add('pattern')
            ->add('fullPattern')
        ;
    }
}
