<?php
namespace Success\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of DayArrayType
 *
 * @author develop1
 */
class DayArrayType extends AbstractType {
    
    public function getParent() {
        return 'sonata_type_immutable_array';
    }

    public function getName() {
        return 'day_array';
    }
}
