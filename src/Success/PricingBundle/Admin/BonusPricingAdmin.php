<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class BonusPricingAdmin extends Admin
{
    use \Success\PricingBundle\Traits\BonusPricingManagerTrait;

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        //$collection->remove('edit');
        //$collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('pricingName', 'text', [])

                ->add('pricingValues', 'sonata_type_collection', array(
                    'required' => false,
                    'cascade_validation' => true,
                    'by_reference' => true,
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                ))
                    
/*

 *
 * 
 *  */
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('pricingName')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('pricingName');
    }

    public function getNewInstance()
    {
        return $this->bonusPricingManager->copyBonusPricingFromCurrent();
    }
}
