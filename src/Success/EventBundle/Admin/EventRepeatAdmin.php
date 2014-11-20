<?php
namespace Success\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;


/**
 * Description of EventTypeAdmin
 *
 * @author develop1
 */
class EventRepeatAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('repeatType', 'choice', array('choices' => array('null' => 'не повторять',  'D'=>'день', 'W'=>'неделя', 'M' => 'месяц', 'Y' => 'год')))
            ->add('repeatInterval', 'integer', array())
            ->add('endDateTime', 'sonata_type_date_picker',
                array(
                    'dp_side_by_side'       => true,
                    'dp_use_current'        => false,
                ))                
        ;
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
//        $datagridMapper
//            ->add('name')
//        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
//        $listMapper
//            ->addIdentifier('name')
//        ;
    }
}
