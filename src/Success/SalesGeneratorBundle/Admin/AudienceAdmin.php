<?php
namespace Success\SalesGeneratorBundle\Admin;

use Sonata\AdminBundle\Admin\Admin,
    Sonata\AdminBundle\Datagrid\ListMapper,
    Sonata\AdminBundle\Datagrid\DatagridMapper,
    Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Route\RouteCollection;

/**
 * @author develop1
 */
class AudienceAdmin extends Admin
{
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', ['label' => 'Name'])
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
            ->remove('batch')
            ->add('id')
            ->addIdentifier('name')
        ;
    }
    
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');

    }
}
