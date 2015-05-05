<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BonusPricingValueAdmin extends Admin
{
    use \Success\PricingBundle\Traits\BonusPricingManagerTrait;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('salesCount', 'integer', [])
                ->add('profitValue', 'number', [])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('salesCount')
                ->add('profitValue')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('salesCount')
                ->add('profitValue')
        ;
    }

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $instance->setPricing($this->bonusPricingManager->getCurrentBonusPricing());
        return $instance;
    }
}
