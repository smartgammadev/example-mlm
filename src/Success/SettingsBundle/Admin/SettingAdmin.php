<?php
namespace Success\SettingsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Description of EventTypeAdmin
 *
 * @author develop1
 */

class SettingAdmin extends Admin {
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Setting Name','read_only' => true))
            ->add('settingValue', 'text', array('label' => 'Setting Value'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }


    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->remove('create');
        $collection->remove('delete');
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('settingValue')
        ;
    }
}
