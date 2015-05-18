<?php

namespace Success\PricingBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use JMS\JobQueueBundle\Entity\Job;
use Sonata\AdminBundle\Route\RouteCollection;

class BonusCalculateSheduleAdmin extends Admin
{
    
    use \Gamma\Framework\Traits\DI\SetEntityManagerTrait;

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('result', $this->getRouterIdParameter().'/result');
        $collection->add('approve', $this->getRouterIdParameter().'/approve');
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('startDate')
                ->add('startDate', 'sonata_type_datetime_picker', array(
                    'format' => 'YYYY-MM-dd HH:mm:ss ZZ',
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => true,
                    'model_timezone' => 'Europe/Moscow',
                    'view_timezone' => 'Europe/Moscow',
                ))
                ->add('calculationDays', 'integer')
                ->add('autoRecreate', null, array('required' => false))
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('id')
                ->add('startDate')
                ->add('calculationDays')
                ->add('autoRecreate')
                ->add('isProcessed', null, ['label' => 'Расчитано'])
                ->add('isApproved', null, ['label' => 'Начислено'])
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                        'result' => array('template' => 'SuccessPricingBundle:Sonata:Actions\result__action.html.twig'),
                        'approve' => array('template' => 'SuccessPricingBundle:Sonata:Actions\approve__action.html.twig'),
                    )
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
                ->add('startDate')
                ->add('isProcessed')
                ->add('calculationDays')
                ->add('autoRecreate')
                ;
    }

    public function getNewInstance()
    {
        $newInsatnce = parent::getNewInstance();
        $defaultDateStart = new \DateTime();
        $defaultDateStart->modify('+30 days');
        
        $newInsatnce->setStartDate($defaultDateStart);
        $newInsatnce->setIsProcessed(false);
        $newInsatnce->setCalculationDays(30);
        return $newInsatnce;
    }
    
    public function postPersist($object)
    {
        $job = $this->createCalculationConsoleJob($object->getStartDate(), $object->getCalculationDays(), $object->getId());        
        $object->setJob($job);
        $this->em->persist($job);
        $this->em->flush();
    }
    
    public function postUpdate($object)
    {
        $job = $object->getJob();
        $job->setExecuteAfter($object->getStartDate());
        $this->em->persist($job);
        $this->em->flush();
    }
    
    private function createCalculationConsoleJob(\DateTime $executionDateTime, $daysToCalculate, $calculateId)
    {
        $consoleCommand = 'success:bonus:calculate';
        $params = [$calculateId];
        $job = new Job($consoleCommand, $params);
        $job->setExecuteAfter($executionDateTime);
        return $job;
    }
}
