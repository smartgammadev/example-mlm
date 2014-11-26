<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


/**
 * Description of EventTypeAdmin
 *
 * @author develop1
 */
class EventRepeatAdmin extends Admin {
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper            
            ->add('repeatType', 'choice', array('label' => 'тип', 'choices' => array(null => 'не повторять',  'D'=>'день', 'W'=>'неделя', 'M' => 'месяц', 'Y' => 'год')))               
            ->add('repeatInterval', 'integer', array('label' => 'интервал'))
            ->add('endDateTime', 'sonata_type_date_picker',
                array(
                    'label' => 'окончание',
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                ))
            ->add('repeatDays', 'sonata_type_immutable_array', 
                    array('label' => 'дни повторения', 'label_attr' => array('style' => 'display:inline'),
                        'keys' => array(
                            array(1, 'checkbox', array('label' => 'Пн')),
                            array(2, 'checkbox', array('label' => 'Вт')),
                            array(3, 'checkbox', array('label' => 'Ср')),
                            array(4, 'checkbox', array('label' => 'Чт')),
                            array(5, 'checkbox', array('label' => 'Пт')),
                            array(6, 'checkbox', array('label' => 'Сб')),
                            array(0, 'checkbox', array('label' => 'Вс')),
                    )))
            ->remove('_delete')
        ;
    }    
}
