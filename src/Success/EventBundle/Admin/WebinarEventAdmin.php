<?php

namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class WebinarEventAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'startDateTime' // name of the ordered field (default = the model id field, if any)
    );

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection
                ->add('cancel_repeats', 'cancel_repeats/{id}', array('_controller' => 'SuccessEventBundle:EventCRUD:cancelRepeats'), array('id' => '\d+')
                )
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->with('Мероприятие')
                ->add('name', 'text', array('label' => 'название'))
                ->add('description', 'textarea', array('label' => 'описание'))
                ->add('url', 'url', array('label' => 'ссылка'))
                ->add('eventType', 'entity', array('label' => 'тип', 'class' => 'Success\EventBundle\Entity\EventType'))
                ->add('media', 'sonata_type_model', array('label' => 'изображение'), array('link_parameters' => array('context' => 'webinar_image',
                        'provider' => 'sonata.media.provider.image')))
                ->add('pattern', 'text', array('label' => 'Webinar Pattern'))
                ->end()
                ->with('Планирование мероприятия')
                ->add('startDateTime', 'sonata_type_datetime_picker', array(
                    'format' => 'YYYY-MM-dd HH:mm:ss ZZ',
                    'dp_side_by_side' => true,
                    'dp_use_current' => false,
                    'dp_use_seconds' => true,
                    'model_timezone' => 'Europe/Moscow',
                    'view_timezone' => 'Europe/Moscow',
            ));
        if ($this->id($this->getSubject())) {
            $formMapper->add('eventRepeat', 'sonata_type_admin', array('required' => false, 'label' => 'Повторять событие'), array('edit' => 'inline'));
        }
        $formMapper->end();

        $formMapper
                ->with('Доступ')
                ->add('accessType', 'entity', array('label' => 'тип доступа', 'class' => 'Success\EventBundle\Entity\EventAccessType'))
                ->add('password', 'text', array('label' => 'пароль', 'required' => false))
                ->end()
        ;
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
                ->add('startDateTime', 'datetime', array('format' => 'd.m.Y H:i'))
                ->addIdentifier('name')
                ->add('eventType', 'text', array())
                ->add('accessType', 'text', array())
                ->add('url', 'url', array('hide_protocol' => false))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }

    public function getNewInstance()
    {
        $newInstance = parent::getNewInstance();
        $newInstance->setUrl('http://');
        return $newInstance;
    }

    /**
     * @param Success\EventBundle\Entity\BaseEvent $object
     */
    public function preUpdate($object)
    {
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $eventRepeat = $object->getEventRepeat();
        if ($eventRepeat->getRepeatType() == null) {
            $object->setEventRepeat(null);
            $em->remove($eventRepeat);
            $em->flush();
        }
    }
//    public function getTemplate($name)
//    {
//        switch ($name) {
//            case 'edit':
//                return 'SuccessEventBundle:Admin:edit.html.twig';
//                break;
//            default:
//                return parent::getTemplate($name);
//                break;
//        }
//    }
}
