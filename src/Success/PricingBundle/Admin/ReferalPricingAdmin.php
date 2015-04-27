<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ReferalPricingAdmin extends Admin
{

    use \Success\PricingBundle\Traits\ReferalPricingManagerTrait;

    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('edit');
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->with('Name')
                ->add('name', 'text', array('label' => 'Pricing Name'))
                ->add('levelsUp', 'integer', array('label' => 'Levels Count'))
                ->add('created', 'datetime', array('widget' => 'single_text', 'read_only' => true, 'label' => 'Updated'))
                ->end()
                ->with('Referal pricing values')
                ->add('pricingValues', 'sonata_type_collection', array(
                    'btn_add' => false,
                    'type_options' => array(
                            'delete' => false,
                        )
                        ), array(
                            'edit' => 'inline',
                            'inline' => 'table',
                        )
                    )
                ->end()
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
                ->addIdentifier('name')
        ;
    }

    public function getNewInstance()
    {
        return $this->referalPricingManager->copyReferalPricingFromCurrent();
    }

    public function postPersist($object)
    {
        $newLevelsUp = $object->getLevelsUp();
        $oldLevelsUp = $object->getPricingValues()->count();

        if ($newLevelsUp == $oldLevelsUp) {
            return;
        }
        if ($newLevelsUp > $oldLevelsUp) {
            $countOfLevelsToAdd = $newLevelsUp - $oldLevelsUp;
            $this->referalPricingManager->addLevelsToReferalPricing($object, $countOfLevelsToAdd);
        }
        if ($newLevelsUp < $oldLevelsUp) {
            $countOfLevelsToRemove = $oldLevelsUp - $newLevelsUp;
            $this->referalPricingManager->removeLevelsFromReferalPricing($object, $countOfLevelsToRemove);
//            var_dump($countOfLevelsToRemove);
//            die('remove');
        }
    }
}
