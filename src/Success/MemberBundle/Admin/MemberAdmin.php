<?php

namespace Success\MemberBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MemberAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('id')
                ->add('externalId')
                
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('id')
                ->add('externalId')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id')
                ->add('memberName', null, ['template' => 'SuccessMemberBundle:Sonata:Fields/member_name.html.twig'])
                ->add('externalId', null, ['label' => 'email'])
                ->add('sponsorName', null, ['template' => 'SuccessMemberBundle:Sonata:Fields/sponsor_name.html.twig'])
                ->add('referalsCount', null, ['template' => 'SuccessMemberBundle:Sonata:Fields/member_refs_count.html.twig'])
                ->add('productPricing', null, ['template' => 'SuccessMemberBundle:Sonata:Fields/member_product_pricing_name.html.twig'])
                ->add('accountBalance', null, ['template' => 'SuccessMemberBundle:Sonata:Fields/member_account_balance.html.twig'])
                
        ;
    }
}
