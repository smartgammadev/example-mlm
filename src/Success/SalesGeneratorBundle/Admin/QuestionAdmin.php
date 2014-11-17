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
            ->add('id', 'doctrine_orm_callback', [
                'label' => 'Audience number',
                'callback' => [$this, 'getQuestionsForAudience']
            ])
            ->add('text')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->remove('batch')
            ->add('id', 'text', [
                'template' => 'SuccessSalesGeneratorBundle:Admin/fields:question_id.html.twig',
                'label' => 'Question order'
            ])
            ->addIdentifier('text', 'text', [
                'template' => 'SuccessSalesGeneratorBundle:Admin/fields:question_text_field.html.twig',
                'label' => 'Question'
            ])
        ;
    }
    
    public function getQuestionsForAudience($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return;
        }
        
        $queryBuilder->andWhere($alias. ".id LIKE '" . $value['value'] . "%'");

        return true;
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
}
