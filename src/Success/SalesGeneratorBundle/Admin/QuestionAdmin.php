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
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'id',
    );
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('audience')
            ->add('text', 'textarea', ['label' => 'Question'])
            ->add('answers', 'sonata_type_collection', ['by_reference' => true,'cascade_validation' => false], [
               'allow_delete'=>true,
               'edit' => 'inline',
               'sortable' => 'position',
               'inline' => 'table'
            ])
        ;
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
        ;
    }
    
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'SuccessSalesGeneratorBundle:Admin:question_edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
    
    public function preRemove($question)
    {
        /* @var $container ContainerInterface */
        $container = $this->getConfigurationPool()->getContainer();
        
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $em = $container->get('doctrine.orm.default_entity_manager');
        
        // Remove all answers for current question
        foreach($question->getAnswers() as $answer) {
            $question->removeAnswer($answer);
            $em->remove($em->getRepository('SuccessSalesGeneratorBundle:Answer')->findOneById($answer->getId()));
        }
        
        $em->flush();
    }
}
