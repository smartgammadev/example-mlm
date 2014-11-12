<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class WebinarEventAdmin extends Admin {
       
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'startDateTime' // name of the ordered field (default = the model id field, if any)
    );
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->add('startDateTime', 'datetime', array('label' => 'Date & Time'))
            ->add('startDateTime', 'sonata_type_datetime_picker',
                    array(
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                    'dp_use_seconds'        => false,
                    'view_timezone'             => 'Europe/Kiev',
                ))
            ->add('url', 'url',array('label' => 'Webinar URL'))    
            //->add('url', 'text', array('label' => 'Webinar URL'))
            ->add('name', 'text', array('label' => 'Webinar Name'))
            ->add('description', 'textarea', array('label' => 'Webinar Description'))
            ->add('pattern', 'text', array('label' => 'Webinar Pattern'))
            ->add('password', 'text', array('label' => 'Webinar Password','required' => false))
            ->add('eventType', 'sonata_type_model', array('class' => 'Success\EventBundle\Entity\EventType'))
            ->add('accessType', 'sonata_type_model', array('class' => 'Success\EventBundle\Entity\EventAccessType'))
            ->add('media', 'sonata_type_model', array('label' => 'Webinar Image'),
                    array('link_parameters' => array('context' =>'webinar_image',
                    'provider' => 'sonata.media.provider.image')))
       ; 
//            ->add('media', 'sonata_media_type', array(
//                 'label' => 'Webinar Image',
//                 'provider' => 'sonata.media.provider.image',
//                 'context'  => 'webinar_event'
//            ));
//        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('startDateTime', 'doctrine_orm_date_range', array(), 'sonata_type_date_range', array('format' => 'dd.MM.yyyy', 'widget' => 'single_text', 'attr' => array('class' => 'datepicker')))
            ->add('name')
            ->add('url')
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('startDateTime','datetime',array('format' => 'd.m.Y H:i'))            
            ->add('name')
            ->add('url', 'url',array('hide_protocol' => false))
            ->add('_action', 'actions', array(
                    'actions' => array(
                    'edit'      => array(),    
                    'delete'      => array(),
                )
            ))
        ;
    }
    
    public function getNewInstance() {
        $newInstance = parent::getNewInstance();
        $newInstance->setUrl('http://');
        return $newInstance;
    }
    
}
