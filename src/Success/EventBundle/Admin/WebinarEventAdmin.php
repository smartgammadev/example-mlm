<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class WebinarEventAdmin extends Admin {
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('startDateTime', 'datetime', array('label' => 'Date & Time'))
            ->add('url', 'text', array('label' => 'Webinar URL'))
            ->add('name', 'text', array('label' => 'Webinar Name'))
            ->add('pattern', 'text', array('label' => 'Webinar Pattern'))
            ->add('password', 'text', array('label' => 'Webinar Password'))
            ->add('eventType', 'entity', array('class' => 'Success\EventBundle\Entity\EventType'))
            ->add('accessType', 'entity', array('class' => 'Success\EventBundle\Entity\EventAccessType'))
        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('url')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('url')
        ;
    }

}
