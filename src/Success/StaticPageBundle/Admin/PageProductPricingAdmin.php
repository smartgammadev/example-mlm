<?php

namespace Success\StaticPageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PageProductPricingAdmin extends Admin{

        protected function configureFormFields(FormMapper $formMapper)
        {
            $formMapper
                    ->add('productPricing', 'entity', array('label' => 'Пакет', 'class' => 'Success\PricingBundle\Entity\ProductPricing'))
                    ->add('page', 'entity', array('label' => 'Страница', 'class' => 'Success\StaticPageBundle\Entity\Page','attr'=>array("hidden" => true)))
            ;
        }

        protected function configureDatagridFilters(DatagridMapper $datagridMapper)
        {
            $datagridMapper
                    ->add('page')
                    ->add('productPricing');
        }

        protected function configureListFields(ListMapper $listMapper)
        {
            $listMapper
                    ->add('page')
                    ->add('productPricing');
        } 
        public function getNewInstance()
        {
            $instance = parent::getNewInstance();

            $pageId  = $this->request->get('objectId', null);
            if ($pageId !== NULL){
                $entityManager = $this->getModelManager()->getEntityManager('Success\StaticPageBundle\Entity\Page');
                $page = $entityManager->getReference('Success\StaticPageBundle\Entity\Page', $pageId);
                $instance->setPage($page);
            }
        return $instance;        
        }
}
