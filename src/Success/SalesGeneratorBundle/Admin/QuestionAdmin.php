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
class QuestionAdmin extends Admin
{    
    protected $baseRouteName = 'questions';    
    protected $baseRoutePattern = 'questions';
    
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'id',
    );
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Question')
                ->add('audience', 'entity', ['class' => 'Success\SalesGeneratorBundle\Entity\Audience'])
                ->add('text', 'textarea', ['label' => 'Question'])
            ->end();
        
        if ($this->id($this->getSubject()))
            $formMapper->with('Answers')                    
                ->add('answers', 'sonata_type_collection', ['by_reference' => true,'cascade_validation' => false], [
                    'allow_delete'=>true,
                    'edit' => 'inline',
                    'sortable' => 'position',
                    'inline' => 'table'
                ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('text')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->remove('batch')
            ->add('id')
            ->addIdentifier('text', 'text', [
                'template' => 'SuccessSalesGeneratorBundle:Admin/fields:question_text_field.html.twig',
                'label' => 'Question'
            ])
            ->addIdentifier('audience')
        ;
    }
    
    /** Set question to be the first in audience if there are no questions at all
     * 
     * @param Success\SalesGeneratorBundle\Entity\Question $question
     */
    public function postPersist($question)
    {
        /* @var $container ContainerInterface */
        $container = $this->getConfigurationPool()->getContainer();
        
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $em = $container->get('doctrine.orm.default_entity_manager');
        
        $audience = $question->getAudience();        
        if ($audience->getFirstQuestion() == NULL) {
            $audience->setFirstQuestion($question);
            $em->flush();
        }
    }
    
    /** Removes all relations for current question
     * 
     * @param Success\SalesGeneratorBundle\Entity\Question $question
     */
    public function preRemove($question)
    {
        /* @var $container ContainerInterface */
        $container = $this->getConfigurationPool()->getContainer();
        
        /* @var $salesGeneratorManager \Success\SalesGeneratorBundle\Service\SalesGeneratorManager */
        $salesGeneratorManager = $container->get('success.sales_generator.manager');
        
        $salesGeneratorManager->removeAllQuestionRelations($question);
    }
}