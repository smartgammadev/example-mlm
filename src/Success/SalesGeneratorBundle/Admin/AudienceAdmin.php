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
    /**
     * @DI\Inject("success.sales_generator.manager")
     * @var \Success\SalesGeneratorBundle\Service\SalesGeneratorManager
     */
    private $salesGeneratorManager;
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', ['label' => 'Name'])
            ->add('firstQuestion')
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
            ->add('firstQuestion')
        ;
    }
    
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');

    }
    
    /** Remove all questions in $audience
     * 
     * @param Success\SalesGeneratorBundle\Entity\Audience $audience
     */
    public function preRemove($audience)
    {
        /* @var $container ContainerInterface */
        $container = $this->getConfigurationPool()->getContainer();
        
        /* @var $salesGeneratorManager \Success\SalesGeneratorBundle\Service\SalesGeneratorManager */
        $salesGeneratorManager = $container->get('success.sales_generator.manager');
        
        $salesGeneratorManager->removeAllAudienceRelations($audience);
    }
}
