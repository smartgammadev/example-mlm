<?php
namespace Success\EventBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class SignupType extends AbstractType {

    private $pm;
    private $eventId;
    public function __construct($placeholderManager, $eventId) {
        $this->pm = $placeholderManager;
        $this->eventId = $eventId;
        //$this->placeholders = $placeholders;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phValues=$this->pm->getPlaceholdersValuesFormSession();
        
        foreach ($phValues as $phValue){
            if (($phValue['placeholder']->getPlaceholderType()->getPattern()=='user')&&
                ($phValue['placeholder']->getAllowUserToEdit())){
                    $builder->add($phValue['placeholder']->getFullPattern(),
                        'text',array('label'=>$phValue['placeholder']->getName(),'data'=>$phValue['value']));               
            }
        }
        
        $builder->add('notify', 'checkbox',array(
            'label' => 'Напомнить перед началом события',
            'required'  => false,
            'data' => true,
            'disabled' => false));
        $builder->setAction('/calendarevents/calendar/event/'.$this->eventId.'/signup');
        $builder->setMethod('POST');
        $builder->add('Записаться', 'submit');
    }
    
    public function getName()
    {
        return 'signup';
    }
}
