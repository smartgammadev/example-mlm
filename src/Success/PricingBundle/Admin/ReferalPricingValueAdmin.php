<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ReferalPricingValueAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('isAbsoluteValue', 'choice', ['required' => true, 'choices' => [true => 'absolute', false => 'percent',]])
                ->add('level', null, ['read_only' => true])
                ->add('profitValue', 'number', [])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('level', 'number', [])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('level', 'number', []);
    }
}
