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
class AnswerAdmin extends Admin
{
        
    protected function configureFormFields(FormMapper $formMapper)
    {
        // Pass following question's id as 'data' parameter in the following operator
        
        // Create custon answer creation template
        // Make sure it fills with current subject(see last bookmark in firefox)
        $formMapper
            ->add('text')
            ->add('nextQuestion')
        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('text')            
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->remove('batch')
            ->add('id')
            ->addIdentifier('question')
            ->addIdentifier('text')
        ;
    }
    
    public function getTemplate($name)
    {
        if ('list' == $name) {
            $this->setTemplate('list', 'ClasscorFacebookAudiencesBundle:Admin:facebookaudiences.html.twig');
        }
        
        return parent::getTemplate($name);
    }
}
